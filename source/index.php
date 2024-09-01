<?php

get_header();

get_template_part('php/component/header');

if (!isset($_GET['jpage']) || empty($_GET['jpage'])) {
    get_template_part('php/component/download');
} else {
    if ($_GET['jpage'] === 'pedantix') {
        get_template_part('php/component/pedantix');
    }

    if ($_GET['jpage'] === 'cemantix') {
        get_template_part('php/component/cemantix');
    }
}

get_footer();
