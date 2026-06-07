<?php
if (!defined('ABSPATH')) exit;

function meziva_theme_setup() {
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('woocommerce');
    add_theme_support('custom-logo');

    register_nav_menus([
        'primary' => __('Desktop Header Menu', 'meziva'),
        'mobile'  => __('Mobile Header Menu', 'meziva'),
        'footer'  => __('Footer Menu', 'meziva'),
    ]);
}
add_action('after_setup_theme', 'meziva_theme_setup');

function meziva_assets() {
    wp_enqueue_style('meziva-style', get_stylesheet_uri(), [], '1.0');

    $output_css = get_template_directory() . '/assets/css/output.css';
    wp_enqueue_style(
        'meziva-tailwind',
        get_template_directory_uri() . '/assets/css/output.css',
        [],
        file_exists($output_css) ? filemtime($output_css) : '1.0'
    );

    $theme_js = get_template_directory() . '/assets/js/theme.js';
    wp_enqueue_script(
        'meziva-theme',
        get_template_directory_uri() . '/assets/js/theme.js',
        [],
        file_exists($theme_js) ? filemtime($theme_js) : '1.0',
        true
    );

    wp_localize_script('meziva-theme', 'meziva_ajax', [
        'ajax_url'   => admin_url('admin-ajax.php'),
        'cart_url'   => function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart'),
        'account_url'=> function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account'),
    ]);
}
add_action('wp_enqueue_scripts', 'meziva_assets');

function meziva_get_option($key, $default = '') {
    return get_theme_mod($key, $default);
}

function meziva_get_menu_items($location = 'primary') {
    $locations = get_nav_menu_locations();
    $menu_id = $locations[$location] ?? 0;
    return $menu_id ? wp_get_nav_menu_items($menu_id) : [];
}

function meziva_get_menu_json($location = 'primary') {
    $items = meziva_get_menu_items($location);
    $data = [];
    if ($items) {
        foreach ($items as $item) {
            $data[] = [
                'id'     => (int) $item->ID,
                'parent' => (int) $item->menu_item_parent,
                'title'  => $item->title,
                'url'    => $item->url,
            ];
        }
    }
    return $data;
}

class Meziva_Desktop_Menu_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($depth > 0) return;
        $title = esc_html($item->title);
        $url = esc_url($item->url);
        $has_children = in_array('menu-item-has-children', $item->classes ?? [], true);
        $is_new = in_array('new', array_map('strtolower', $item->classes ?? []), true);

        if ($has_children) {
            $output .= '<button type="button" class="meziva-mega-trigger relative hover:text-[#93aa52] transition" data-menu-id="' . esc_attr($item->ID) . '">';
            if ($is_new) $output .= '<span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>';
            $output .= $title . ' <span class="text-sm">⌄</span></button>';
        } else {
            $output .= '<a class="relative hover:text-[#93aa52] transition" href="' . $url . '">';
            if ($is_new) $output .= '<span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>';
            $output .= $title . '</a>';
        }
    }
}

class Meziva_Mobile_Menu_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($depth > 0) return;
        $title = esc_html($item->title);
        $url = esc_url($item->url);
        $has_children = in_array('menu-item-has-children', $item->classes ?? [], true);
        $is_new = in_array('new', array_map('strtolower', $item->classes ?? []), true);

        if ($has_children) {
            $output .= '<button type="button" class="meziva-mobile-sub-open w-full border-b border-[#9ab25b] py-3 text-lg flex items-center justify-between" data-menu-id="' . esc_attr($item->ID) . '" data-title="' . esc_attr($title) . '">';
            $output .= '<span>' . $title . ($is_new ? ' <span class="bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>' : '') . '</span><span>›</span></button>';
        } else {
            $output .= '<a class="block border-b border-[#9ab25b] py-3 text-lg" href="' . $url . '">' . $title;
            if ($is_new) $output .= ' <span class="bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>';
            $output .= '</a>';
        }
    }
}

function meziva_customize_register($wp_customize) {
    $wp_customize->add_section('meziva_header_settings', [
        'title' => __('Meziva Header Settings', 'meziva'),
        'priority' => 30,
    ]);

    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("meziva_topbar_slide_{$i}_line_1", [
            'default' => $i === 1 ? 'Product Of The Month : Milk Drops Brightening Serum' : '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("meziva_topbar_slide_{$i}_line_1", [
            'label' => "Topbar Slide {$i} - Line 1",
            'section' => 'meziva_header_settings',
            'type' => 'text',
        ]);

        $wp_customize->add_setting("meziva_topbar_slide_{$i}_line_2", [
            'default' => $i === 1 ? 'Use code HURRY20 & Get FLAT 20% OFF' : '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);
        $wp_customize->add_control("meziva_topbar_slide_{$i}_line_2", [
            'label' => "Topbar Slide {$i} - Line 2",
            'section' => 'meziva_header_settings',
            'type' => 'text',
        ]);
    }

    $wp_customize->add_setting('meziva_mobile_banner', ['sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'meziva_mobile_banner', [
        'label' => __('Mobile Menu Banner', 'meziva'),
        'section' => 'meziva_header_settings',
        'settings' => 'meziva_mobile_banner',
    ]));

    $wp_customize->add_setting('meziva_mega_banner', ['sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'meziva_mega_banner', [
        'label' => __('Mega Menu Banner', 'meziva'),
        'section' => 'meziva_header_settings',
        'settings' => 'meziva_mega_banner',
    ]));

    $wp_customize->add_setting('meziva_track_order_url', ['default' => home_url('/track-order'), 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control('meziva_track_order_url', [
        'label' => __('Track Order URL', 'meziva'),
        'section' => 'meziva_header_settings',
        'type' => 'url',
    ]);

    $wp_customize->add_setting('meziva_whatsapp_url', ['default' => 'https://wa.me/', 'sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control('meziva_whatsapp_url', [
        'label' => __('WhatsApp URL', 'meziva'),
        'section' => 'meziva_header_settings',
        'type' => 'url',
    ]);
}
add_action('customize_register', 'meziva_customize_register');

function meziva_ajax_product_search() {
    $keyword = sanitize_text_field($_GET['keyword'] ?? '');
    if (!$keyword) wp_send_json_success([]);

    $query = new WP_Query([
        'post_type' => 'product',
        'posts_per_page' => 12,
        's' => $keyword,
        'post_status' => 'publish',
    ]);

    $products = [];
    while ($query->have_posts()) {
        $query->the_post();
        global $product;
        $products[] = [
            'title' => get_the_title(),
            'price' => $product ? $product->get_price_html() : '',
            'url' => get_permalink(),
            'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: (function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : ''),
        ];
    }
    wp_reset_postdata();
    wp_send_json_success($products);
}
add_action('wp_ajax_meziva_product_search', 'meziva_ajax_product_search');
add_action('wp_ajax_nopriv_meziva_product_search', 'meziva_ajax_product_search');




// Home Bestseller ATC
function meziva_ajax_add_to_cart() {
    if (!function_exists('WC')) {
        wp_send_json_error(['message' => 'WooCommerce not active']);
    }

    $product_id = absint($_POST['product_id'] ?? 0);

    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product']);
    }

    $added = WC()->cart->add_to_cart($product_id, 1);

    if (!$added) {
        wp_send_json_error(['message' => 'Product not added']);
    }

    wp_send_json_success([
        'message' => 'Added to cart',
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_url' => wc_get_cart_url(),
    ]);
}
add_action('wp_ajax_meziva_add_to_cart', 'meziva_ajax_add_to_cart');
add_action('wp_ajax_nopriv_meziva_add_to_cart', 'meziva_ajax_add_to_cart');




// footer 
// Footer Customizer Settings
function meziva_footer_customize_register($wp_customize) {

    $wp_customize->add_section('meziva_footer_settings', [
        'title'    => __('Meziva Footer Settings', 'meziva'),
        'priority' => 31,
    ]);

    $wp_customize->add_setting('meziva_footer_about_title', [
        'default'           => 'Meziva Beauty',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('meziva_footer_about_title', [
        'label'   => __('Footer About Title', 'meziva'),
        'section' => 'meziva_footer_settings',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('meziva_footer_about_text', [
        'default'           => 'Meziva Beauty brings simple, effective and skin-friendly products made for everyday care.',
        'sanitize_callback' => 'sanitize_textarea_field',
    ]);

    $wp_customize->add_control('meziva_footer_about_text', [
        'label'   => __('Footer About Text', 'meziva'),
        'section' => 'meziva_footer_settings',
        'type'    => 'textarea',
    ]);

    $wp_customize->add_setting('meziva_footer_menu_title', [
        'default'           => 'Quick Links',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('meziva_footer_menu_title', [
        'label'   => __('Footer Menu Title', 'meziva'),
        'section' => 'meziva_footer_settings',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('meziva_footer_social_title', [
        'default'           => 'Follow us on',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('meziva_footer_social_title', [
        'label'   => __('Social Title', 'meziva'),
        'section' => 'meziva_footer_settings',
        'type'    => 'text',
    ]);

    $wp_customize->add_setting('meziva_footer_email', [
        'default'           => 'contact@meziva.in',
        'sanitize_callback' => 'sanitize_email',
    ]);

    $wp_customize->add_control('meziva_footer_email', [
        'label'   => __('Footer Email', 'meziva'),
        'section' => 'meziva_footer_settings',
        'type'    => 'email',
    ]);

    $wp_customize->add_setting('meziva_footer_phone', [
        'default'           => '+91 85806 24176',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('meziva_footer_phone', [
        'label'   => __('Footer Phone', 'meziva'),
        'section' => 'meziva_footer_settings',
        'type'    => 'text',
    ]);

    $socials = [
        'facebook'  => 'Facebook URL',
        'twitter'   => 'X / Twitter URL',
        'instagram' => 'Instagram URL',
        'pinterest' => 'Pinterest URL',
        'youtube'   => 'YouTube URL',
    ];

    foreach ($socials as $key => $label) {
        $wp_customize->add_setting("meziva_social_{$key}", [
            'default'           => '',
            'sanitize_callback' => 'esc_url_raw',
        ]);

        $wp_customize->add_control("meziva_social_{$key}", [
            'label'   => __($label, 'meziva'),
            'section' => 'meziva_footer_settings',
            'type'    => 'url',
        ]);
    }

    $wp_customize->add_setting('meziva_footer_copyright', [
        'default'           => 'All rights reserved.',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('meziva_footer_copyright', [
        'label'   => __('Copyright Text', 'meziva'),
        'section' => 'meziva_footer_settings',
        'type'    => 'text',
    ]);
}
add_action('customize_register', 'meziva_footer_customize_register');