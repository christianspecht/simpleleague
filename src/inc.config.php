<?php

Class DbSettings {
    
    // ########################################
    // DEFINE DATABASE SETTINGS HERE
    public $db_host = "localhost";
    public $db_name = "phpsimpleleague";
    public $db_user = "root";
    public $db_pass = "";
    // ######################################## 
    
}

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

?>
