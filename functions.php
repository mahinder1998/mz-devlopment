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
     wp_enqueue_style(
        'meziva-tailwind',
        get_template_directory_uri() . '/assets/css/output.css',
        [],
        filemtime(
            get_template_directory() . '/assets/css/output.css'
        )
    );

}

add_action(
    'wp_enqueue_scripts',
    'meziva_assets'
);