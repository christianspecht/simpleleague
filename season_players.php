<?php
require_once 'inc.settings.php';

if (isset($_GET['season_name']))
{
    $season = $_GET['season_name'];
    
    $db = connect_db();

    $sql = "select p.player_name, t.team_name
            from seasons_players sp
            inner join seasons s on sp.season_id = s.season_id
            inner join players p on sp.player_id = p.player_id
            left join teams t on sp.team_id = t.team_id
            where season_name = ?
            order by p.player_name";
    
    $query = $db->prepare($sql);
    $query->bindParam(1, $season, PDO::PARAM_STR);
    $query->setFetchMode(PDO::FETCH_OBJ);
    $query->execute();
    
    $data = new Settings();
    
    echo $tpl->render('season_players', $data);

}

?>
