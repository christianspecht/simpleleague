<?php

require_once 'inc.settings.php';

if (isset($_GET['season_name']) && isset($_GET['round_number']))
{
    $season = $_GET['season_name'];
    $round = $_GET['round_number'];
    
    $db = connect_db();

    $sql = "select r.round_number, p.player_name, g.player1_points as points, g.player1_victorypoints as vp, g.player2_victorypoints * -1 as opponent_vp, g.player1_result_id as result_id
            from seasons s
            inner join rounds r on r.season_id = s.season_id
            inner join games g on g.round_id = r.round_id
            inner join players p on p.player_id = g.player1_id
            where s.season_name = :season and r.round_number between 1 and :round
            and r.finished = 1
            union
            select r.round_number, p.player_name, g.player2_points, g.player2_victorypoints, g.player1_victorypoints * -1, g.player2_result_id
            from seasons s
            inner join rounds r on r.season_id = s.season_id
            inner join games g on g.round_id = r.round_id
            inner join players p on p.player_id = g.player2_id
            where s.season_name = :season and r.round_number between 1 and :round
            and r.finished = 1
            order by round_number";
    
    $query = $db->prepare($sql);
    $query->bindParam(':season', $season, PDO::PARAM_STR);
    $query->bindParam(':round', $round, PDO::PARAM_INT);
    $query->setFetchMode(PDO::FETCH_ASSOC);
    $query->execute();
    
    $data = new Settings();
    $data->rows = $query->fetchAll();
    
    echo $tpl->render('season_results', $data);
}

?>
