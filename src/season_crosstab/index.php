<?php

require_once '../inc.settings.php';

if (isset($_GET['season_name']))
{
    $season = $_GET['season_name'];

    $db = connect_db();
    $data = new Settings();
    
    // Query 1: all players in the current season
    $sql1 = "select p.player_id, p.player_name
            from seasons_players sp
            inner join seasons s on sp.season_id = s.season_id
            inner join players p on sp.player_id = p.player_id
            where season_name = :season
            order by p.player_name";

    $query1 = $db->prepare($sql1);
    $query1->bindParam(':season', $season, PDO::PARAM_STR);
    $query1->setFetchMode(PDO::FETCH_ASSOC);
    $query1->execute();
    
    $players = $query1->fetchAll();
    
    // Query 2: all finished games in the current season
    $sql2 = "select p1.player_name as player1_name, p2.player_name as player2_name, 
                g.player1_victorypoints, g.player2_victorypoints,
                g.player1_id, g.player2_id,
                re1.result_id as result1_id, re2.result_id as result2_id,
                re1.description as result1, re2.description as result2,
                re1.description_short as result1_short, re2.description_short as result2_short,
                r.round_number
            from seasons s
            inner join rounds r on s.season_id = r.season_id
            inner join games g on r.round_id = g.round_id
            left join players p1 on p1.player_id = g.player1_id
            left join players p2 on p2.player_id = g.player2_id
            left join results re1 on re1.result_id = g.player1_result_id
            left join results re2 on re2.result_id = g.player2_result_id
            where s.season_name = :season
            and r.finished = 1
            and g.player2_id <> 0
            order by p1.player_name, p2.player_name";
            
    $query2 = $db->prepare($sql2);
    $query2->bindParam(':season', $season, PDO::PARAM_STR);
    $query2->setFetchMode(PDO::FETCH_ASSOC);
    $query2->execute();
    
    $games = $query2->fetchAll();

    $lines = array();
    
    // empty basic table (rows and columns with player names, no results)
    foreach($players as $player) {
    
        $tmp = array();
        $tmp['player_id'] = $player['player_id'];
        $tmp['player_name'] = $player['player_name'];
        $tmp['results'] = array();
        
        foreach($players as $player2) {
            $result = array();
            $result['player_id'] = $player2['player_id'];
            $result['player_name'] = $player2['player_name'];
            $result['points1'] = 'XXX';
            $tmp['results'][] = $result;
        }
        
        $lines[] = $tmp;
    }
    
    // loop all games and put results into the table from step 1
    $resultlist = array();
    
    foreach($games as $game) {
    
        foreach ($lines as &$line) {
        
            if ($line['player_id'] == $game['player1_id']) {
            
                foreach($line['results'] as &$result) {
                
                    if ($result['player_id'] == $game['player2_id']) {
                    
                        $result['points1'] = $game['player1_victorypoints'];
                        $result['points2'] = $game['player2_victorypoints'];
                        $result['round_number'] = $game['round_number'];
                        
                        /*
                        determine the winner
                        - If one player has a result != 0 and the other player has a result == 0, the first player wins
                        - If both players have the same result, but it's not 0, then it's a draw
                        */
                        $resultlist_new = array();
                        
                        if ($game['result1_id'] != 0 && $game['result2_id'] == 0) {
                            // player 1 (the "home" player") wins
                            $result['result'] = $game['result1_short'];
                            $result['bgcolor'] = $data->html_bgcolor_win;
                            
                            $resultlist_new['id'] = $game['result1_id'];
                            $resultlist_new['short'] = $game['result1_short'];
                            $resultlist_new['desc'] = $game['result1'];
                            
                        } elseif ($game['result1_id'] == 0 && $game['result2_id'] != 0) {
                            // player 2 (the "guest") wins
                            $result['result'] = $game['result2_short'];
                            $result['bgcolor'] = $data->html_bgcolor_loss; // use the color for loss, because the "home" player loses
                            
                            $resultlist_new['id'] = $game['result2_id'];
                            $resultlist_new['short'] = $game['result2_short'];
                            $resultlist_new['desc'] = $game['result2'];
                            
                        } elseif ($game['result1_id'] != 0 && $game['result2_id'] != 0 && $game['result1_id'] == $game['result2_id']) {
                            // draw
                            $result['result'] = $game['result1_short'];
                            $result['bgcolor'] = $data->html_bgcolor_draw;
                            
                            $resultlist_new['id'] = $game['result1_id'];
                            $resultlist_new['short'] = $game['result1_short'];
                            $resultlist_new['desc'] = $game['result1'];
                        }
                        
                        // save the result description (only once per result) for the explanation below the cross table
                        if (isset($resultlist_new['short'])) {
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
                        
                        break;
                    }
                }
                
                break;
            }
        }
    }
    
    $data->players = $players;
    $data->lines = $lines;
    $data->resultlist = $resultlist;

    $template = "season_crosstab";
    if (isset($_GET['custom_template']))
    {
        $template = $_GET['custom_template'];
    }

    echo $tpl->render($template, $data);
}

?>
