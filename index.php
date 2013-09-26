<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title></title>
    </head>
    <body>
        <?php
        
        // settings (XAMPP on localhost)
        $host = "localhost";
        $dbname = "phpsimpleleague";
        $user = "root";
        $pass = "";
        
        try {
            $db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);
        }
        catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        $query = $db->query('select * from players');
        $query->setFetchMode(PDO::FETCH_OBJ);
        
        while($row = $query->fetch()) {
            echo $row->player_id . " " . $row->player_name;
            echo "<br>";
        }
        
        ?>
    </body>
</html>
