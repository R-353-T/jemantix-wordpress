<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . "/php/utils.php";
require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

$isMigrate = get_option("jemantix_leaderboard_script", 0);
if ($isMigrate === 0) {
    add_option("jemantix_leaderboard_script", 1);
    echo "MIGRATE !!!";
    dbDelta("CREATE TABLE IF NOT EXISTS jemantix_leaderboard (
        id INT(11) primary key NOT NULL auto_increment,
        date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
        nickname VARCHAR(64) NOT NULL,
        score INT NOT NULL,
        gamemode INT NOT NULL);");
}

if (!empty($_POST['gamemode'])) {
    header("Access-Control-Allow-Origin: https://cemantix.certitudes.org");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
    header("Access-Control-Allow-Headers: Content-Type, Authorization");

    if (
        !empty($_POST['nickname']) &&
        !empty($_POST['score']) &&
        !empty($_POST['gamemode'])
    ) {
        global $wpdb;
        $wpdb->insert(
            "jemantix_leaderboard",
            array(
                "nickname" => $_POST['nickname'],
                "score" => intval($_POST['score']),
                "gamemode" => intval($_POST['gamemode'])
            )
        );

        echo json_encode(["ok" => true]);
    } else {
        echo json_encode(["error" => $_POST]);
    }
    die();
}


add_action("wp_enqueue_scripts", "jemantix_action_frontend_enqueue");
