<?php

Class Settings {
    
    // ########################################
    // DEFINE LABELS HERE
    // (will be displayed in HTML tables)
    public $label_player = "Player";
    public $label_season = "Season";
    public $label_team = "Team";
    public $label_round = "Round";
    public $label_vs = "vs.";
    public $label_nogame = "no game";
    public $label_points = "Points";
    public $label_victorypoints = "Victory Points";
    public $label_victorypoints_difference = "Diff.";
    public $label_point_separator = ":";
    public $label_result = "Result";
    public $label_number_of_games = "No. of games";
    // ########################################
    
    // ########################################
    // OTHER SETTINGS
    // Properties for HTML tables, will be put into the <table> tag
    public $html_table_properties = "border='1'";
    // additional properties for "schedule per season" table:
    public $html_schedule_table_properties = "style='width:250px'";
    // ########################################
}

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

require 'Mustache/Autoloader.php';
Mustache_Autoloader::register();

$tpl = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/tpl'),
    'cache' => dirname(__FILE__).'/cache',
));

?>
