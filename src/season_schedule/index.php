<?php
require_once '../inc.settings.php';

if (isset($_GET['season_name']))
{
    $season = $_GET['season_name'];
    $round = 0;
    
    if (isset($_GET['round_number'])) {
        $round = $_GET['round_number'];
    }
    
    $db = connect_db();
    
    $sql = "select r.round_number, r.description, p1.player_name as player1_name, p2.player_name as player2_name, g.player1_id, g.player2_id
            from seasons s
            inner join rounds r on r.season_id = s.season_id
            inner join games g on g.round_id = r.round_id
            left join players p1 on p1.player_id = g.player1_id
            left join players p2 on p2.player_id = g.player2_id
            where s.season_name = ?";
    
    if ($round > 0) {
        $sql .= " and r.round_number = ?"; 
    } else {
        $sql .= " and r.round_number > 0"; 
    }
    
    $sql .= " order by r.round_number, game_id";

    $query = $db->prepare($sql);
    $query->bindParam(1, $season, PDO::PARAM_STR);
    
    if ($round > 0) {
        $query->bindParam(2, $round, PDO::PARAM_INT);
    }
    
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    
    $data = new Settings();
    $data->rounds = array();
    
    $lastround = 0;
 
    while($row = $query->fetch()) {
        
        $round = $row['round_number'];
        
        if ($round != $lastround) {
            
            $tmp = array();
            $tmp['round_number'] = $round;
            $tmp['description'] = $row['description'];
            $data->rounds[] = $tmp;
            
            $lastround = $round;
        }
        
        $tmp = array();
        
        if ($row['player1_id'] == 0) {
            $tmp['player1_name'] = $data->label_nogame;
        } else {
            $tmp['player1_name'] = $row['player1_name'];
        }

        if ($row['player2_id'] == 0) {
            $tmp['player2_name'] = $data->label_nogame;
        } else {
            $tmp['player2_name'] = $row['player2_name'];
        }
        
        if ($row['player1_id'] != 0 && $row['player2_id'] != 0) {
            $tmp['vs'] = $data->label_vs;
        }

        
        // get the key of the last inserted item (http://stackoverflow.com/a/10044716/6884)
        end($data->rounds);
        $lastkey = key($data->rounds);
        
        $data->rounds[$lastkey]['games'][] = $tmp;
    }
    
    echo $tpl->render('season_schedule', $data);
 }

?>
