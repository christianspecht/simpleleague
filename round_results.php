<?php
require_once 'inc.settings.php';

if (isset($_GET['season_name']) && isset($_GET['round_number']))
{
    $season = $_GET['season_name'];
    $round = $_GET['round_number'];
    
    $db = connect_db();

    $sql = "select p1.player_name as player1_name, p2.player_name as player2_name, 
                g.player1_victorypoints, g.player2_victorypoints,
                g.player1_points, g.player2_points,
                g.player1_id, g.player2_id, re.description as result
            from seasons s
            inner join rounds r on s.season_id = r.season_id
            inner join games g on r.round_id = g.round_id
            left join players p1 on p1.player_id = g.player1_id
            left join players p2 on p2.player_id = g.player2_id
            left join results re on re.result_id = g.result_id
            where s.season_name = ? and r.round_number = ?
            order by g.game_id";
    
    $query = $db->prepare($sql);
    $query->bindParam(1, $season, PDO::PARAM_STR);
    $query->bindParam(2, $round, PDO::PARAM_INT);
    $query->setFetchMode(PDO::FETCH_OBJ);
    $query->execute();
    
    echo "<table $HTML_TABLE_PROPERTIES>\n";
    echo "<tr style='font-weight:bold;'>
    <td colspan='3'>$LABEL_ROUND $round</td>
    <td colspan='3'>$LABEL_VICTORYPOINTS</td>
    <td></td>
    <td colspan='3'>$LABEL_POINTS</td>
    <td>$LABEL_RESULT</td>
</tr>\n";
    
    foreach($query as $row) {

        if ($row->player1_id == 0) {
            $name1 = $LABEL_NOGAME;
        } else {
            $name1 = $row->player1_name;    
        }
        
        if ($row->player2_id == 0) {
            $name2 = $LABEL_NOGAME;
        } else {
            $name2 = $row->player2_name;    
        }
        
        if ($row->player1_id == 0 || $row->player2_id == 0 ) {
            
            $name_vs = "";
            
            $vp1 = "";
            $vp_vs = "";
            $vp2 = "";

            $p1 = "";
            $p_vs = "";
            $p2 = "";
            
            $res = "";
            
        } else {
            
            $name_vs = $LABEL_VS;
            
            $vp1 = $row->player1_victorypoints;
            $vp_vs = $LABEL_POINT_SEPARATOR;
            $vp2 = $row->player2_victorypoints;

            $p1 = $row->player1_points;
            $p_vs = $LABEL_POINT_SEPARATOR;
            $p2 = $row->player2_points;
            
            $res = $row->result;
            
        }

        echo "<tr>
    <td>$name1</td>
    <td>$name_vs</td>
    <td>$name2</td>
    <td>$vp1</td>
    <td>$vp_vs</td>
    <td>$vp2</td>
    <td></td>
    <td>$p1</td>
    <td>$p_vs</td>
    <td>$p2</td>
    <td>$res</td>
</tr>\n";        
        
    }
    
    echo "</table>\n";
    
}

?>
