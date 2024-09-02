<?php

/**
 * Advanced enqueue_script
 * @param string $name
 * @param string $relative_path
 * @param string[] $dependencies
 */
function jemantix_wp_enqueue_script(string $name, string $relative_path, array $dependencies = []): void
{
    wp_enqueue_script(
        "jemantix_{$name}_script",
        get_template_directory_uri() . $relative_path,
        $dependencies,
        filemtime(get_template_directory() . $relative_path),
        true
    );
}

/**
 * Advanced enqueue_style
 * @param string $name
 * @param string $relative_path
 * @param array $dependencies
 */
function jemantix_wp_enqueue_style(string $name, string $relative_path, array $dependencies = []): void
{
    wp_enqueue_style(
        "jemantix_{$name}_style",
        get_template_directory_uri() . $relative_path,
        $dependencies,
        filemtime(get_template_directory() . $relative_path),
        "all"
    );
}

/**
 * Load frontend style and script
 */
function jemantix_action_frontend_enqueue(): void
{
    jemantix_wp_enqueue_style("frontend", "/index.min.css", ["dashicons"]);
    jemantix_wp_enqueue_script("frontend", "/index.min.js");
}


function displayRows($rows)
{
    $position = 1;
    $date_format = 'Y-m-d H:i:s';
    $fr_format = 'H:i:s';

    foreach ($rows as $row) {
        $date = DateTime::createFromFormat($date_format, $row->date);
        ?>
        <tr>
            <td><?php echo $position; ?></td>
            <td><?php echo esc_html($row->nickname); ?></td>
            <td><?php echo esc_html($row->score); ?></td>
            <td><?php echo $date->format($fr_format); ?></td>
        </tr>
        <?php

        $position++;
    }

    if (count($rows) === 0) {
        echo "<tr> <td>-</td> <td>Aucun résultat</td> <td>-</td> <td>-</td> </tr>";
    }
}

function displayTodayCemantixRows()
{
    date_default_timezone_set("Europe/Paris");
    $date_format = 'Y-m-d H:i:s';
    $date = DateTime::createFromFormat($date_format, date('Y-m-d') . " 00:00:00");

    global $wpdb;
    $query = "SELECT * FROM jemantix_leaderboard "
    . " WHERE gamemode = 1 AND date >= '" . $date->format($date_format) . "'"
    . " ORDER BY score ASC LIMIT 50";

    $results = $wpdb->get_results($query, OBJECT);
    displayRows($results);
}

function displayYesterdayCemantixRows()
{
    date_default_timezone_set("Europe/Paris");
    $date_format = 'Y-m-d H:i:s';

    $start = DateTime::createFromFormat(
        $date_format,
        date('Y-m-d', strtotime("-1 days")) . " 00:00:00"
    );

    $end = DateTime::createFromFormat(
        $date_format,
        date('Y-m-d') . " 00:00:00"
    );

    global $wpdb;
    $query = "SELECT * FROM jemantix_leaderboard "
    . " WHERE gamemode = 1 AND date <= '" . $end->format($date_format) . "'"
    . " AND date >= '" . $start->format($date_format) . "'"
    . " ORDER BY score ASC LIMIT 50";

    $results = $wpdb->get_results($query, OBJECT);
    displayRows($results);
}

function displayTodayPedantixRows()
{
    date_default_timezone_set("Europe/Paris");
    global $wpdb;
    $date_format = 'Y-m-d H:i:s';
    $current_date = DateTime::createFromFormat($date_format, date('Y-m-d H:i:s'));
    $current_date_time = getdate($current_date->getTimestamp());

    if ($current_date_time["hours"] >= 12) {
        $start = DateTime::createFromFormat($date_format, date('Y-m-d') . " 12:00:00");
    } else {
        $start = DateTime::createFromFormat($date_format, date('Y-m-d', strtotime("-1 days")) . " 12:00:00");
    }

    $query = "SELECT * FROM jemantix_leaderboard "
        . " WHERE gamemode = 2 AND date >= '" . $start->format($date_format) . "'"
        . " ORDER BY score ASC LIMIT 50";
    $results = $wpdb->get_results($query, OBJECT);
    displayRows($results);
}

function displayYesterdayPedantixRows()
{
    date_default_timezone_set("Europe/Paris");
    global $wpdb;
    $date_format = 'Y-m-d H:i:s';
    $current_date = DateTime::createFromFormat($date_format, date('Y-m-d H:i:s'));
    $current_date_time = getdate($current_date->getTimestamp());

    if ($current_date_time["hours"] >= 12) {
        $start = DateTime::createFromFormat($date_format, date('Y-m-d', strtotime("-1 days")) . " 12:00:00");
        $end = DateTime::createFromFormat($date_format, date('Y-m-d') . " 12:00:00");
    } else {
        $start = DateTime::createFromFormat($date_format, date('Y-m-d', strtotime("-2 days")) . " 12:00:00");
        $end = DateTime::createFromFormat($date_format, date('Y-m-d', strtotime("-1 days")) . " 12:00:00");
    }

    $query = "SELECT * FROM jemantix_leaderboard "
    . " WHERE gamemode = 2 AND date >= '" . $start->format($date_format) . "'"
    . " AND date <= '" . $end->format($date_format) . "'"
    . " ORDER BY score ASC LIMIT 50";

    $results = $wpdb->get_results($query, OBJECT);
    displayRows($results);
}