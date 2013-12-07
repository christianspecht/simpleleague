<?php

require_once 'inc.config.php';

function connect_db()
{
    $settings = new DbSettings();
    
    try {
        $db = new PDO("mysql:host=$settings->db_host;dbname=$settings->db_name", $settings->db_user, $settings->db_pass);
    }
    catch (PDOException $e) {
        echo $e->getMessage();
    }    
    
    return $db;
}

// sorting function for ranking lists 
function sort_ranking($a, $b) {
    
    // order by points first
    $sort= $b['points'] - $a['points'];
    
    // if points are equal, order by difference
    if ($sort == 0) {
        $sort= $b['diff'] - $a['diff'];
    }

    // if differences are equal as well, order by victory points
    if ($sort == 0) {
        $sort= $b['vp'] - $a['vp'];
    }
    
    // if victory points are equal AS WELL, order by player name
    if ($sort == 0) {
        $sort = strcasecmp($a['player_name'], $b['player_name']);
    }
    
    return $sort;
}


require 'Mustache/Autoloader.php';
Mustache_Autoloader::register();

$tpl = new Mustache_Engine(array(
    'loader' => new Mustache_Loader_FilesystemLoader(dirname(__FILE__) . '/tpl'),
    'cache' => dirname(__FILE__).'/cache',
    'escape' => function($value) {
        return custom_escape($value);
    },
));

?>
