<?php

add_theme_support('title-tag');
add_theme_support('post-thumbnails');
add_theme_support('woocommerce');

function meziva_assets() {

    wp_enqueue_style(
        'meziva-style',
        get_stylesheet_uri(),
        [],
        '1.0'
    );

}

add_action(
    'wp_enqueue_scripts',
    'meziva_assets'
);