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

     wp_enqueue_style('meziva-style', get_stylesheet_uri(), [], '1.0');
     wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', [], null, false);
     wp_enqueue_script(
            'meziva-theme',
            get_template_directory_uri() . '/assets/js/theme.js',
            [],
            '1.0',
            true
        );

        wp_localize_script('meziva-theme', 'meziva_ajax', [
            'ajax_url' => admin_url('admin-ajax.php'),
        ]);

}

add_action(
    'wp_enqueue_scripts',
    'meziva_assets'
);



function meziva_theme_setup() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('woocommerce');
  register_nav_menus([
    'primary' => __('Primary Menu', 'meziva'),
  ]);
}
add_action('after_setup_theme', 'meziva_theme_setup');

// function meziva_assets() {
//   wp_enqueue_style('meziva-style', get_stylesheet_uri(), [], '1.0');

//   wp_enqueue_script('tailwind-cdn', 'https://cdn.tailwindcss.com', [], null, false);

//   wp_enqueue_script(
//     'meziva-theme',
//     get_template_directory_uri() . '/assets/js/theme.js',
//     [],
//     '1.0',
//     true
//   );

//   wp_localize_script('meziva-theme', 'meziva_ajax', [
//     'ajax_url' => admin_url('admin-ajax.php'),
//   ]);
// }
add_action('wp_enqueue_scripts', 'meziva_assets');

function meziva_ajax_product_search() {
  $keyword = sanitize_text_field($_GET['keyword'] ?? '');

  if (!$keyword) {
    wp_send_json_success([]);
  }

  $query = new WP_Query([
    'post_type' => 'product',
    'posts_per_page' => 12,
    's' => $keyword,
  ]);

  $products = [];

  while ($query->have_posts()) {
    $query->the_post();
    global $product;

    $products[] = [
      'title' => get_the_title(),
      'price' => $product ? $product->get_price_html() : '',
      'url' => get_permalink(),
      'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: wc_placeholder_img_src(),
    ];
  }

  wp_reset_postdata();

  wp_send_json_success($products);
}
add_action('wp_ajax_meziva_product_search', 'meziva_ajax_product_search');
add_action('wp_ajax_nopriv_meziva_product_search', 'meziva_ajax_product_search');