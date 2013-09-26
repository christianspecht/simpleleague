<?php
require_once 'inc.settings.php';

if (isset($_GET['season_name']))
{
    $season = $_GET['season_name'];
    
    $db = connect_db();
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $query = $db->query("select * from seasons where season_name = '$season'");
    $query->setFetchMode(PDO::FETCH_OBJ);
       
    while($row = $query->fetch()) {
        echo $row->season_id ." ".$row->season_name;
    }
}

?>
