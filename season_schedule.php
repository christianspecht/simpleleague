<?php
require_once 'inc.settings.php';

if (isset($_GET['season_name']))
{
    $season = $_GET['season_name'];
    
    $db = connect_db();
    
    $sql = "select r.round_number, r.description, p1.player_name as player1, p2.player_name as player2
            from seasons s
            inner join rounds r on r.season_id = s.season_id
            inner join games g on g.round_id = r.round_id
            inner join players p1 on p1.player_id = g.player1_id
            inner join players p2 on p2.player_id = g.player2_id
            where s.season_name = ?
            order by game_id";

    $query = $db->prepare($sql);
    $query->bindParam(1, $season, PDO::PARAM_STR);
    $query->setFetchMode(PDO::FETCH_OBJ);
    $query->execute();

    $last_round_number = 0;
    
    foreach($query as $row) {
        
        if ($last_round_number != $row->round_number) {
            
            if ($last_round_number != 0) {
                echo "</table>";
                echo "<p></p>";
            }
            
            echo "<table $HTML_TABLE_PROPERTIES>
                <tr><td colspan='3'>$LABEL_ROUND $row->round_number</td></tr>";
            
            if (!empty($row->description)) {
                echo "<tr><td colspan='3'>$row->description</td></tr>";
            }
        }
        
        echo "<tr>
            <td>$row->player1</td>
            <td>$LABEL_VS</td>
            <td>$row->player2</td>
            </tr>";
        
        $last_round_number = $row->round_number;
    }
    
    if ($last_round_number > 0) {
        echo "</table>";
    }
}

?>
