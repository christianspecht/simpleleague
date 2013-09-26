<?php
require_once 'inc.settings.php';

if (isset($_GET['season_name']))
{
    $season = $_GET['season_name'];
    
    $db = connect_db();

    $query = $db->prepare('select * from seasons where season_name = ?');
    $query->bindParam(1, $season, PDO::PARAM_STR);
    $query->setFetchMode(PDO::FETCH_OBJ);
    $query->execute();
    
    foreach($query as $row) {
        echo $row->season_id ." ".$row->season_name;
    }
}

?>
