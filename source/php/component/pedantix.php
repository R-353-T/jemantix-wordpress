<?php

/**
 * Cémantix
 */

?>

<div class="leaderboard">
    <h2>Pédantix</h2>

    <section>
        <h3>Aujourd'hui</h3>
        <table>
            <tr>
                <th></th>
                <th>Surnom</th>
                <th>Score</th>
                <th></th>
            </tr>
            <?php displayTodayPedantixRows(); ?>
        </table>
    </section>

    <section>
        <h3>Hier</h3>
        <table>
            <tr>
                <th></th>
                <th>Surnom</th>
                <th>Score</th>
                <th></th>
            </tr>
            <?php displayYesterdayPedantixRows(); ?>
        </table>
    </section>
</div>