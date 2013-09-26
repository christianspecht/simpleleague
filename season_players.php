<?php
require_once 'inc.settings.php';

$db = connect_db();

$query = $db->query('select * from players');
$query->setFetchMode(PDO::FETCH_OBJ);

while($row = $query->fetch()) {
    echo $row->player_id . " " . $row->player_name;
}

?>
