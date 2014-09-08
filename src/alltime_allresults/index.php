<?php

require_once '../inc.settings.php';

// return the cell color depending on the game result
function allresults_cellcolor($data, $player_result_id, $opponent_result_id) {

    if ($player_result_id == $opponent_result_id) {
        return $data->html_bgcolor_draw;
    } elseif ($player_result_id > 0) {
        return $data->html_bgcolor_win;
    } else {
        return $data->html_bgcolor_loss;
    }
}


$blank = ""; // content for blank cell

$db = connect_db();
$data = new Settings();

// Query 1: all players from all seasons
$sql1 = "select distinct p.player_id, p.player_name
        from seasons s
        inner join seasons_players sp on s.season_id = sp.season_id
        inner join players p on sp.player_id = p.player_id
        where s.no_statistics = 0
        order by p.player_name, p.player_id";

$query1 = $db->prepare($sql1);
$query1->setFetchMode(PDO::FETCH_ASSOC);
$query1->execute();
    
$players = $query1->fetchAll();

// Query 2: all games from all seasons
$sql2 = "select g.game_id, s.season_name, s.season_id, r.round_number, r.finished,
        g.player1_id, g.player2_id, g.player1_result_id, g.player2_result_id, 
        re1.description_short as result1, re2.description_short as result2,
        re1.description as result1_desc, re2.description as result2_desc
        from games g
        inner join rounds r on g.round_id = r.round_id
        inner join seasons s on r.season_id = s.season_id
        left join results re1 on g.player1_result_id = re1.result_id
        left join results re2 on g.player2_result_id = re2.result_id
        where s.no_statistics = 0
        order by s.season_name, s.season_id, r.round_number, g.game_id";

$query2 = $db->prepare($sql2);
$query2->setFetchMode(PDO::FETCH_ASSOC);
$query2->execute();
$games = $query2->fetchAll();

// create basic array for results
$rows = array();
foreach($players as $player) {
    $tmp = array();
    $tmp['player_id'] = $player['player_id'];
    $tmp['player_name'] = $player['player_name'];
    $tmp['results'] = array();
    $rows[] = $tmp;
}

// 1. loop all games, create the basic table with seasons and rounds
$seasons = array();
$rounds = array();

$last_season_id = 0;
$last_round_number = 0;

$current_season = array();
$current_season['colspan'] = 0;

$new_round = array();
$round_players = $players;

foreach($games as $game) {
    
    // new round?
    if ($last_round_number != $game['round_number']) {
    
        $new_round = array();
        $new_round['round_number'] = $game['round_number'];
        
        if ($last_round_number != 0) {
            // increment colspan (the cell for the season needs to span the cells for the rounds)
            $current_season['colspan']++;
        }
    }
    
    // new season?
    if ($last_season_id != $game['season_id'] && $last_season_id != 0) {
    
        // insert the current season
        $seasons[] = $current_season;
        $current_season = array();
        $current_season['colspan'] = 0;
        
        // insert blank cells
        $tmp = array();
        $tmp['round_number'] = $blank;
        $rounds[] = $tmp;
        
        $tmp = array();
        $tmp['season_name'] = $blank;
        $seasons[] = $tmp;
        
        foreach($rows as &$row) {
            $tmp = array();
            $tmp['result'] = $blank;
            $row['results'][] = $tmp;
        }
    }
    
    // save new round from above (can't save above, because if there are blank 
    // cells because of a new season, they need to be inserted first)
    if (isset($new_round['round_number'])) {
    
        // the actual round
        $rounds[] = $new_round;
        
        // one cell per player
        foreach($rows as &$row) {
            $tmp = array();
            $tmp['result'] = "";
            $tmp['bgcolor'] = $data->html_bgcolor_allresults_empty;
            $tmp['season_id'] = $game['season_id'];
            $tmp['round_number'] = $game['round_number'];
            $row['results'][] = $tmp;
        }
        
        $new_round = array();
    }
    
    $last_season_id = $game['season_id'];
    $last_round_number = $game['round_number'];
    $current_season['season_name'] = $game['season_name'];
}

// save the last season
$current_season['colspan']++;
$seasons[] = $current_season;


// 2. loop the games again, insert results
$resultlist = array();
foreach($games as $game) {

    if ($game['finished']) {

        foreach($rows as &$row) {
        
            $resultlist_new = array();
        
            if ($game['player1_id'] == $row['player_id'] && $game['player2_id'] != 0) {
                foreach($row['results'] as &$result) {
                    if (isset($result['season_id']) && $result['season_id'] == $game['season_id'] && $result['round_number'] == $game['round_number']) {
                        $result['result'] = $data->label_home_short . $game['result1'];
                        $result['bgcolor'] = allresults_cellcolor($data, $game['player1_result_id'], $game['player2_result_id']);
                        
                        $resultlist_new['id'] = $game['player1_result_id'];
                        $resultlist_new['short'] = $game['result1'];
                        $resultlist_new['desc'] = $game['result1_desc'];
                        
                        break;
                    }
                }
            }
            
            if ($game['player2_id'] == $row['player_id'] && $game['player1_id'] != 0) {
                foreach($row['results'] as &$result) {
                    if (isset($result['season_id']) && $result['season_id'] == $game['season_id'] && $result['round_number'] == $game['round_number']) {
                        $result['result'] = $data->label_guest_short . $game['result2'];
                        $result['bgcolor'] = allresults_cellcolor($data, $game['player2_result_id'], $game['player1_result_id']);
                        
                        $resultlist_new['id'] = $game['player2_result_id'];
                        $resultlist_new['short'] = $game['result2'];
                        $resultlist_new['desc'] = $game['result2_desc'];
                        
                        break;
                    }
                }
            }
            
            if (isset($resultlist_new['id'])) {
                $exists = 0;
                foreach($resultlist as $resultlist_item) {
                    if ($resultlist_item['id'] == $resultlist_new['id']) {
                        $exists = 1;
                        break;
                    }
                }
                if (!$exists) {
                    $resultlist[] = $resultlist_new;
                }

            }
            
        }
    }
}

$data->seasons = $seasons;
$data->rounds = $rounds;
$data->rows = $rows;
$data->resultlist = $resultlist;

echo $tpl->render('alltime_allresults', $data);

?>
