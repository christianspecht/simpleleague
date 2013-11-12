<?php
require_once 'inc.settings.php';

if (isset($_GET['season_name']) && isset($_GET['round_number']))
{
    $season = $_GET['season_name'];
    $round = $_GET['round_number'];
    
    $db = connect_db();

    $sql = "select p1.player_name as player1_name, p2.player_name as player2_name, 
                g.player1_victorypoints, g.player2_victorypoints,
                g.player1_points, g.player2_points,
                g.player1_id, g.player2_id,
                re1.description as result1, re2.description as result2,
                r.finished
            from seasons s
            inner join rounds r on s.season_id = r.season_id
            inner join games g on r.round_id = g.round_id
            left join players p1 on p1.player_id = g.player1_id
            left join players p2 on p2.player_id = g.player2_id
            left join results re1 on re1.result_id = g.player1_result_id
            left join results re2 on re2.result_id = g.player2_result_id
            where s.season_name = ? and r.round_number = ?
            order by g.game_id";
    
    $query = $db->prepare($sql);
    $query->bindParam(1, $season, PDO::PARAM_STR);
    $query->bindParam(2, $round, PDO::PARAM_INT);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    
    $results = $query->fetchAll();
    
    $data = new Settings();
    $data->round_number = $round;
    
    foreach($results as &$row) {
        
        if ($row['player1_id'] == 0) {
            $row['player1_name'] = $data->label_nogame;
        }
        
        if ($row['player2_id'] == 0) {
            $row['player2_name'] = $data->label_nogame;
        }
        
        $row['vs'] = $data->label_vs;
        $row['separator'] = $data->label_point_separator;

        if ($row['finished'] == 0 || $row['player1_id'] == 0 || $row['player2_id'] == 0) {
            
            if ($row['player1_id'] == 0 || $row['player2_id'] == 0) {
                $row['vs'] = "";
            }
            
            $row['separator'] = "";
            $row['player1_victorypoints'] = "";
            $row['player2_victorypoints'] = "";
            $row['player1_points'] = "";
            $row['player2_points'] = "";
            
        } else {
          
            // display the result of the higher scoring player
            if ($row['player1_points'] > $row['player2_points']) {
                $row['result'] = $row['result1'];
            } else {
                $row['result'] = $row['result2'];
            }
        }
    }
    
    $data->results = $results;
    
    echo $tpl->render('round_results', $data);
}

?>
