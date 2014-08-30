<?php

require_once '../inc.settings.php';

if (isset($_GET['season_name']) && isset($_GET['round_number']))
{
    $season = $_GET['season_name'];
    $round = $_GET['round_number'];

    $show_unfinished = 0;
    if (isset($_GET['show_unfinished']))
    {
        $show_unfinished = $_GET['show_unfinished'];
    }

    $db = connect_db();

    $sql = "select r.round_number, p.player_id, p.player_name, g.player1_points as points, g.player1_victorypoints as vp, g.player2_id as opponent_id, g.player2_victorypoints as opponent_vp, g.player1_result_id as result_id, r.finished
            from seasons s
            inner join rounds r on r.season_id = s.season_id
            inner join games g on g.round_id = r.round_id
            inner join players p on p.player_id = g.player1_id
            where s.season_name = :season and r.round_number <= :round
            union
            select r.round_number, p.player_id, p.player_name, g.player2_points, g.player2_victorypoints, g.player1_id, g.player1_victorypoints, g.player2_result_id, r.finished
            from seasons s
            inner join rounds r on r.season_id = s.season_id
            inner join games g on g.round_id = r.round_id
            inner join players p on p.player_id = g.player2_id
            where s.season_name = :season and r.round_number <= :round
            order by round_number";
    
    $query = $db->prepare($sql);
    $query->bindParam(':season', $season, PDO::PARAM_STR);
    $query->bindParam(':round', $round, PDO::PARAM_INT);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    
    $rows = $query->fetchAll();    
    
    $data = new Settings();
    $results = array();
    
    foreach($rows as $row) {
        
        $pid = $row['player_id'];
        
        $finished = ($show_unfinished == 1 || $row['finished'] == 1);
        
        if ($finished == 1) {
            
            if (isset($results[$pid])) {

                $results[$pid]['points'] = $results[$pid]['points'] + $row['points'];
                $results[$pid]['vp'] = $results[$pid]['vp'] + $row['vp'];
                $results[$pid]['opponent_vp'] = $results[$pid]['opponent_vp'] + $row['opponent_vp'];
                $results[$pid]['diff'] = $results[$pid]['vp'] - $results[$pid]['opponent_vp'];

            } else {

                $tmp = array();
                $tmp['player_id'] = $row['player_id'];
                $tmp['player_name'] = $row['player_name'];
                $tmp['points'] = $row['points'];
                $tmp['vp'] = $row['vp'];
                $tmp['opponent_vp'] = $row['opponent_vp'];
                $tmp['diff'] = $tmp['vp'] - $tmp['opponent_vp'];
                $tmp['games'] = 0;
                $results[$pid] = $tmp;
            }
         
            if ($row['opponent_id'] != 0) {
                $results[$pid]['games']++;
            }

            if ($results[$pid]['diff'] > 0) {
                $results[$pid]['diff'] = "+".$results[$pid]['diff'];
            }

        }
        
    }
    
    usort($results, "sort_ranking");
    
    $rank = 1;
    foreach($results as &$result) {
        $result['rank'] = $rank;
        $rank++;
    }
    
    $data->results = $results;
    echo $tpl->render('season_ranking', $data);
}

?>
