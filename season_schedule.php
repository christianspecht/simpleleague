<?php
require_once 'inc.settings.php';

if (isset($_GET['season_name']))
{
    $season = $_GET['season_name'];
    
    $db = connect_db();
    
    $sql = "select r.round_number, r.description, p1.player_name as player1_name, p2.player_name as player2_name, g.player1_id, g.player2_id
            from seasons s
            inner join rounds r on r.season_id = s.season_id
            inner join games g on g.round_id = r.round_id
            left join players p1 on p1.player_id = g.player1_id
            left join players p2 on p2.player_id = g.player2_id
            where s.season_name = ? and r.round_number > 0
            order by r.round_number, game_id";

    $query = $db->prepare($sql);
    $query->bindParam(1, $season, PDO::PARAM_STR);
    $query->setFetchMode(PDO::FETCH_OBJ);
    $query->execute();

    $last_round_number = 0;
    
    foreach($query as $row) {
        
        if ($last_round_number != $row->round_number) {
            
            if ($last_round_number != 0) {
                echo "</table>\n";
                echo "<p></p>\n";
            }
            
            echo "<table $HTML_TABLE_PROPERTIES $HTML_SCHEDULE_TABLE_PROPERTIES>
<tr><td colspan='3' style='text-align:center;'><b>$LABEL_ROUND $row->round_number</b></td></tr>\n";
            
            if (!empty($row->description)) {
                echo "<tr><td colspan='3' style='text-align:center;'>$row->description</td></tr>\n";
            }
        }
        
        if ($row->player1_id == 0) {
            $col1 = $LABEL_NOGAME;
        } else {
            $col1 = $row->player1_name;    
        }
        
        if ($row->player1_id == 0 || $row->player2_id == 0) {
            $col2 = "";
        } else {
            $col2 = $LABEL_VS;
        }

        if ($row->player2_id == 0) {
            $col3 = $LABEL_NOGAME;
        } else {
            $col3 = $row->player2_name;    
        }
        echo "<tr>
    <td>$col1</td>
    <td>$col2</td>
    <td>$col3</td>
</tr>\n";
        
        $last_round_number = $row->round_number;
    }
    
    if ($last_round_number > 0) {
        echo "</table>\n";
    }
}

?>
