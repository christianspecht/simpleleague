<?php

// ########################################
// DEFINE LABELS HERE
// (will be displayed in HTML tables)
$LABEL_PLAYER = "Player";
$LABEL_SEASON = "Season";
$LABEL_TEAM = "Team";
$LABEL_ROUND = "Round";
$LABEL_VS = "vs.";
// ########################################


// ########################################
// OTHER SETTINGS
// Properties for HTML tables, will be put into the <table> tag
$HTML_TABLE_PROPERTIES = "border='1'";
// additional properties for "schedule per season" table:
$HTML_SCHEDULE_TABLE_PROPERTIES = "style='width:250px'";
// ########################################

function connect_db()
{
    // ########################################
    // DEFINE DATABASE SETTINGS HERE
    $DB_HOST = "localhost";
    $DB_NAME = "phpsimpleleague";
    $DB_USER = "root";
    $DB_PASS = "";
    // ########################################
    
    try {
        $db = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }    
    
    return $db;
}

?>
