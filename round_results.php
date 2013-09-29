<?php
require_once 'inc.settings.php';

if (isset($_GET['season_name']) && isset($_GET['round_number']))
{
    $season = $_GET['season_name'];
    $round = $_GET['round_number'];
    
    $db = connect_db();

    $sql = "select p1.player_name as player1_name, p2.player_name as player2_name, 
                g.player1_victorypoints, g.player2_victorypoints,
                g.player1_points, g.player2_points
            from seasons s
            inner join rounds r on s.season_id = r.season_id
            inner join games g on r.round_id = g.round_id
            left join players p1 on p1.player_id = g.player1_id
            left join players p2 on p2.player_id = g.player2_id
            where s.season_name = ? and r.round_number = ?
            order by g.game_id";
    
    $query = $db->prepare($sql);
    $query->bindParam(1, $season, PDO::PARAM_STR);
    $query->bindParam(2, $round, PDO::PARAM_INT);
    $query->setFetchMode(PDO::FETCH_OBJ);
    $query->execute();
    
    echo "<table $HTML_TABLE_PROPERTIES>\n";
    echo "<tr>
    <td colspan='3'>$LABEL_ROUND $round</td>
    <td colspan='3'>$LABEL_VICTORYPOINTS</td>
    <td></td>
    <td colspan='3'>$LABEL_POINTS</td>
</tr>\n";
    
    foreach($query as $row) {

        echo "<tr>
    <td>$row->player1_name</td>
    <td>$LABEL_VS</td>
    <td>$row->player2_name</td>
    <td>$row->player1_victorypoints</td>
    <td>$LABEL_POINT_SEPARATOR</td>
    <td>$row->player2_victorypoints</td>
    <td></td>
    <td>$row->player1_points</td>
    <td>$LABEL_POINT_SEPARATOR</td>
    <td>$row->player2_points</td>
</tr>\n";        
        
    }
    
    echo "</table>\n";
    
}

?>
