<?php
if (!defined('ABSPATH')) exit;

/* Theme Setup */
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

/* Assets */
function meziva_assets() {
    wp_enqueue_style(
        'meziva-style',
        get_stylesheet_uri(),
        [],
        filemtime(get_stylesheet_directory() . '/style.css')
    );

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
        'ajax_url'    => admin_url('admin-ajax.php'),
        'cart_url'    => function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart'),
        'account_url' => function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account'),
    ]);
}
add_action('wp_enqueue_scripts', 'meziva_assets');

/* Product Page JS */
function meziva_product_assets() {
    if (function_exists('is_product') && is_product()) {
        $product_js = get_template_directory() . '/assets/js/product.js';

        wp_enqueue_script(
            'meziva-product',
            get_template_directory_uri() . '/assets/js/product.js',
            ['meziva-theme'],
            file_exists($product_js) ? filemtime($product_js) : '1.0',
            true
        );
    }
}
add_action('wp_enqueue_scripts', 'meziva_product_assets');

/* Mini Cart JS */
function meziva_minicart_assets() {
    if (!function_exists('WC')) return;

    $minicart_js = get_template_directory() . '/assets/js/minicart.js';

    wp_enqueue_script(
        'meziva-minicart',
        get_template_directory_uri() . '/assets/js/minicart.js',
        ['jquery'],
        file_exists($minicart_js) ? filemtime($minicart_js) : '1.0',
        true
    );

    wp_localize_script('meziva-minicart', 'meziva_cart_ajax', [
        'ajax_url'     => admin_url('admin-ajax.php'),
        'checkout_url' => function_exists('wc_get_checkout_url') ? wc_get_checkout_url() : home_url('/checkout'),
        'nonce'        => wp_create_nonce('meziva_cart_nonce'),
    ]);
}
add_action('wp_enqueue_scripts', 'meziva_minicart_assets');

/* Helpers */
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

/* Menu Walkers */
class Meziva_Desktop_Menu_Walker extends Walker_Nav_Menu {
    public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
        if ($depth > 0) return;

        $title = esc_html($item->title);
        $url = esc_url($item->url);
        $has_children = in_array('menu-item-has-children', $item->classes ?? [], true);
        $is_new = in_array('new', array_map('strtolower', $item->classes ?? []), true);

        if ($has_children) {
            $output .= '<button type="button" class="meziva-mega-trigger relative hover:text-secondary transition" data-menu-id="' . esc_attr($item->ID) . '">';
            if ($is_new) {
                $output .= '<span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>';
            }
            $output .= $title . ' <span class="text-sm">⌄</span></button>';
        } else {
            $output .= '<a class="relative hover:text-secondary transition" href="' . $url . '">';
            if ($is_new) {
                $output .= '<span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>';
            }
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
            $output .= '<a class="block border-b border-primary-light py-3 text-base" href="' . $url . '">' . $title;
            if ($is_new) {
                $output .= ' <span class="bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>';
            }
            $output .= '</a>';
        }
    }
}

/* Header Customizer */
function meziva_customize_register($wp_customize) {
    $wp_customize->add_section('meziva_header_settings', [
        'title'    => __('Meziva Header Settings', 'meziva'),
        'priority' => 30,
    ]);

    for ($i = 1; $i <= 5; $i++) {
        $wp_customize->add_setting("meziva_topbar_slide_{$i}_line_1", [
            'default'           => $i === 1 ? 'Product Of The Month : Milk Drops Brightening Serum' : '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control("meziva_topbar_slide_{$i}_line_1", [
            'label'   => "Topbar Slide {$i} - Line 1",
            'section' => 'meziva_header_settings',
            'type'    => 'text',
        ]);

        $wp_customize->add_setting("meziva_topbar_slide_{$i}_line_2", [
            'default'           => $i === 1 ? 'Use code HURRY20 & Get FLAT 20% OFF' : '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control("meziva_topbar_slide_{$i}_line_2", [
            'label'   => "Topbar Slide {$i} - Line 2",
            'section' => 'meziva_header_settings',
            'type'    => 'text',
        ]);
    }

    $wp_customize->add_setting('meziva_mobile_banner', ['sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'meziva_mobile_banner', [
        'label'    => __('Mobile Menu Banner', 'meziva'),
        'section'  => 'meziva_header_settings',
        'settings' => 'meziva_mobile_banner',
    ]));

    $wp_customize->add_setting('meziva_mega_banner', ['sanitize_callback' => 'esc_url_raw']);
    $wp_customize->add_control(new WP_Customize_Image_Control($wp_customize, 'meziva_mega_banner', [
        'label'    => __('Mega Menu Banner', 'meziva'),
        'section'  => 'meziva_header_settings',
        'settings' => 'meziva_mega_banner',
    ]));

    $wp_customize->add_setting('meziva_track_order_url', [
        'default'           => home_url('/track-order'),
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control('meziva_track_order_url', [
        'label'   => __('Track Order URL', 'meziva'),
        'section' => 'meziva_header_settings',
        'type'    => 'url',
    ]);

    $wp_customize->add_setting('meziva_whatsapp_url', [
        'default'           => 'https://wa.me/',
        'sanitize_callback' => 'esc_url_raw',
    ]);

    $wp_customize->add_control('meziva_whatsapp_url', [
        'label'   => __('WhatsApp URL', 'meziva'),
        'section' => 'meziva_header_settings',
        'type'    => 'url',
    ]);
}
add_action('customize_register', 'meziva_customize_register');

/* Footer Customizer */
function meziva_footer_customize_register($wp_customize) {
    $wp_customize->add_section('meziva_footer_settings', [
        'title'    => __('Meziva Footer Settings', 'meziva'),
        'priority' => 31,
    ]);

    $fields = [
        'meziva_footer_about_title'  => ['Footer About Title', 'Meziva Beauty', 'text'],
        'meziva_footer_about_text'   => ['Footer About Text', 'Meziva Beauty brings simple, effective and skin-friendly products made for everyday care.', 'textarea'],
        'meziva_footer_menu_title'   => ['Footer Menu Title', 'Quick Links', 'text'],
        'meziva_footer_social_title' => ['Social Title', 'Follow us on', 'text'],
        'meziva_footer_email'        => ['Footer Email', 'contact@meziva.in', 'email'],
        'meziva_footer_phone'        => ['Footer Phone', '+91 85806 24176', 'text'],
        'meziva_footer_copyright'    => ['Copyright Text', 'All rights reserved.', 'text'],
    ];

    foreach ($fields as $key => $field) {
        $sanitize = 'sanitize_text_field';
        if ($field[2] === 'textarea') $sanitize = 'sanitize_textarea_field';
        if ($field[2] === 'email') $sanitize = 'sanitize_email';

        $wp_customize->add_setting($key, [
            'default'           => $field[1],
            'sanitize_callback' => $sanitize,
        ]);

        $wp_customize->add_control($key, [
            'label'   => __($field[0], 'meziva'),
            'section' => 'meziva_footer_settings',
            'type'    => $field[2],
        ]);
    }

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
}
add_action('customize_register', 'meziva_footer_customize_register');

/* Search Customizer */
function meziva_search_customize_register($wp_customize) {
    $wp_customize->add_section('meziva_search_settings', [
        'title'    => __('Meziva Search Settings', 'meziva'),
        'priority' => 32,
    ]);

    $wp_customize->add_setting('meziva_search_heading', [
        'default'           => 'Trending Products | Search',
        'sanitize_callback' => 'sanitize_text_field',
    ]);

    $wp_customize->add_control('meziva_search_heading', [
        'label'   => __('Search Trending Heading', 'meziva'),
        'section' => 'meziva_search_settings',
        'type'    => 'text',
    ]);

    for ($i = 1; $i <= 12; $i++) {
        $wp_customize->add_setting("meziva_trending_search_{$i}", [
            'default'           => '',
            'sanitize_callback' => 'sanitize_text_field',
        ]);

        $wp_customize->add_control("meziva_trending_search_{$i}", [
            'label'   => __("Trending Search {$i}", 'meziva'),
            'section' => 'meziva_search_settings',
            'type'    => 'text',
        ]);
    }
}
add_action('customize_register', 'meziva_search_customize_register');

/* Dynamic Search AJAX */
function meziva_ajax_product_search() {
    $keyword = sanitize_text_field($_GET['keyword'] ?? '');

    if (!$keyword) {
        wp_send_json_success([
            'products'    => [],
            'posts'       => [],
            'collections' => [],
            'pages'       => [],
        ]);
    }

    $products = [];

    $product_query = new WP_Query([
        'post_type'      => 'product',
        'posts_per_page' => 8,
        's'              => $keyword,
        'post_status'    => 'publish',
    ]);

    while ($product_query->have_posts()) {
        $product_query->the_post();
        $product = function_exists('wc_get_product') ? wc_get_product(get_the_ID()) : null;

        $products[] = [
            'title' => get_the_title(),
            'url'   => get_permalink(),
            'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: (function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : ''),
            'price' => $product ? $product->get_price_html() : '',
        ];
    }
    wp_reset_postdata();

    $posts = [];

    $post_query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => 6,
        's'              => $keyword,
        'post_status'    => 'publish',
    ]);

    while ($post_query->have_posts()) {
        $post_query->the_post();

        $posts[] = [
            'title' => get_the_title(),
            'url'   => get_permalink(),
            'image' => get_the_post_thumbnail_url(get_the_ID(), 'thumbnail') ?: '',
            'date'  => get_the_date('d M Y'),
        ];
    }
    wp_reset_postdata();

    $pages = [];

    $page_query = new WP_Query([
        'post_type'      => 'page',
        'posts_per_page' => 6,
        's'              => $keyword,
        'post_status'    => 'publish',
    ]);

    while ($page_query->have_posts()) {
        $page_query->the_post();

        $pages[] = [
            'title' => get_the_title(),
            'url'   => get_permalink(),
        ];
    }
    wp_reset_postdata();

    $collections = [];

    if (function_exists('get_terms') && taxonomy_exists('product_cat')) {
        $terms = get_terms([
            'taxonomy'   => 'product_cat',
            'hide_empty' => true,
            'search'     => $keyword,
            'number'     => 6,
        ]);

        if (!is_wp_error($terms)) {
            foreach ($terms as $term) {
                $thumbnail_id = get_term_meta($term->term_id, 'thumbnail_id', true);
                $image = $thumbnail_id ? wp_get_attachment_image_url($thumbnail_id, 'thumbnail') : (function_exists('wc_placeholder_img_src') ? wc_placeholder_img_src() : '');

                $collections[] = [
                    'title' => $term->name,
                    'url'   => get_term_link($term),
                    'image' => $image,
                    'count' => $term->count,
                ];
            }
        }
    }

    wp_send_json_success([
        'products'    => $products,
        'posts'       => $posts,
        'collections' => $collections,
        'pages'       => $pages,
    ]);
}
add_action('wp_ajax_meziva_product_search', 'meziva_ajax_product_search');
add_action('wp_ajax_nopriv_meziva_product_search', 'meziva_ajax_product_search');

/* Home Bestseller Add To Cart */
function meziva_ajax_add_to_cart() {
    if (!function_exists('WC')) {
        wp_send_json_error(['message' => 'WooCommerce not active']);
    }

    $product_id = absint($_POST['product_id'] ?? 0);

    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product']);
    }

    $added = WC()->cart->add_to_cart($product_id, 1);
    WC()->cart->calculate_totals();

    if (!$added) {
        wp_send_json_error(['message' => 'Product not added']);
    }

    wp_send_json_success([
        'message'    => 'Added to cart',
        'cart_count' => WC()->cart->get_cart_contents_count(),
        'cart_url'   => wc_get_cart_url(),
    ]);
}
add_action('wp_ajax_meziva_add_to_cart', 'meziva_ajax_add_to_cart');
add_action('wp_ajax_nopriv_meziva_add_to_cart', 'meziva_ajax_add_to_cart');

/* PDP Add To Cart */
function meziva_ajax_add_cart() {
    if (!function_exists('WC')) {
        wp_send_json_error(['message' => 'WooCommerce not active']);
    }

    $product_id = absint($_POST['product_id'] ?? 0);
    $qty = max(1, absint($_POST['quantity'] ?? 1));

    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product']);
    }

    $added = WC()->cart->add_to_cart($product_id, $qty);
    WC()->cart->calculate_totals();

    if (!$added) {
        wp_send_json_error(['message' => 'Product not added']);
    }

    wp_send_json_success([
        'count' => WC()->cart->get_cart_contents_count(),
    ]);
}
add_action('wp_ajax_meziva_ajax_add_cart', 'meziva_ajax_add_cart');
add_action('wp_ajax_nopriv_meziva_ajax_add_cart', 'meziva_ajax_add_cart');

/* Mini Cart HTML */
function meziva_get_minicart_html() {
    if (!function_exists('WC') || !WC()->cart) return '';

    ob_start();

    $cart = WC()->cart;
    $cart_count = $cart->get_cart_contents_count();
    $subtotal = (float) $cart->get_subtotal();
    $discount = (float) $cart->get_discount_total();
    $total = (float) $cart->get_total('edit');

    $coupon_target = 1499;
    $progress = $coupon_target > 0 ? min(100, ($subtotal / $coupon_target) * 100) : 0;
    ?>

    <?php if ($cart_count <= 0) : ?>

        <button type="button" class="mz-cart-close mz-empty-close">×</button>

        <div class="mz-empty-cart-v2">
            <div class="mz-empty-icon">
                <span class="mz-empty-zero">0</span>
                <svg width="52" height="52" viewBox="0 0 64 64" fill="none">
                    <path d="M18 22H46L43 52H21L18 22Z" stroke="currentColor" stroke-width="3" stroke-linejoin="round"/>
                    <path d="M25 22C25 14 39 14 39 22" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                    <path d="M27 32C29 35 35 35 37 32" stroke="currentColor" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>

            <h4>Your cart is empty</h4>

            <a href="<?php echo esc_url(wc_get_page_permalink('shop')); ?>" class="mz-empty-btn">
                Continue shopping
            </a>
        </div>

    <?php else : ?>

        <div class="mz-cart-head">
            <div>
                <h3>Your Shopping Cart <span><?php echo esc_html($cart_count); ?></span></h3>
                <p>All rewards unlocked 🎉</p>
            </div>
            <button type="button" class="mz-cart-close">×</button>
        </div>

        <div class="mz-progress-wrap">
            <div class="mz-progress-labels">
                <span></span>
                <span>Free Shipping</span>
                <span>SAVE100</span>
            </div>

            <div class="mz-progress-line">
                <div class="mz-progress-fill" style="width: <?php echo esc_attr($progress); ?>%;"></div>
                <span class="mz-progress-dot mz-dot-one">🚚</span>
                <span class="mz-progress-dot mz-dot-two">🏷️</span>
            </div>

            <div class="mz-progress-price">
                <span></span>
                <span>₹899</span>
                <span>₹1499</span>
            </div>
        </div>

        <div class="mz-cart-items">
            <?php foreach ($cart->get_cart() as $cart_item_key => $cart_item) :
                $_product = $cart_item['data'];
                if (!$_product || !$_product->exists()) continue;

                $product_id = $cart_item['product_id'];
                $qty = $cart_item['quantity'];
                $name = $_product->get_name();
                $price = wc_price($_product->get_price());
                $image = $_product->get_image('thumbnail');
                $short = wp_strip_all_tags($_product->get_short_description());
            ?>
                <div class="mz-cart-item" data-key="<?php echo esc_attr($cart_item_key); ?>">
                    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="mz-cart-img">
                        <?php echo wp_kses_post($image); ?>
                    </a>

                    <div class="mz-cart-info">
                        <h4><?php echo esc_html($name); ?></h4>

                        <?php if ($short) : ?>
                            <p><?php echo esc_html(wp_trim_words($short, 6)); ?></p>
                        <?php endif; ?>

                        <strong><?php echo wp_kses_post($price); ?></strong>

                        <?php if ($discount > 0) : ?>
                            <div class="mz-cart-saving">Saved with coupon</div>
                        <?php endif; ?>
                    </div>

                    <div class="mz-cart-qty">
                        <button type="button" class="mz-cart-remove">🗑</button>
                        <button type="button" class="mz-qty-minus">−</button>
                        <span><?php echo esc_html($qty); ?></span>
                        <button type="button" class="mz-qty-plus">+</button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="mz-save-strip">
            🎟 You’ve saved <?php echo wc_price($discount); ?> with this order
        </div>

        <div class="mz-cart-summary">
            <div>
                <span>MRP</span>
                <strong><?php echo wc_price($subtotal + $discount); ?></strong>
            </div>

            <div>
                <span>You Save</span>
                <strong><?php echo wc_price($discount); ?></strong>
            </div>

            <div class="mz-you-pay">
                <span>You Pay</span>
                <strong><?php echo wc_price($total); ?></strong>
            </div>
        </div>

        <p class="mz-cart-trust">Free Shipping | COD Available | Trusted by 10L+ People</p>

        <a href="<?php echo esc_url(wc_get_checkout_url()); ?>" class="mz-checkout-btn">
            🔒 Checkout
        </a>

    <?php endif; ?>

    <?php
    return ob_get_clean();
}

/* Mini Cart AJAX */
function meziva_ajax_get_minicart() {
    check_ajax_referer('meziva_cart_nonce', 'nonce');

    wp_send_json_success([
        'html'  => meziva_get_minicart_html(),
        'count' => WC()->cart->get_cart_contents_count(),
    ]);
}
add_action('wp_ajax_meziva_get_minicart', 'meziva_ajax_get_minicart');
add_action('wp_ajax_nopriv_meziva_get_minicart', 'meziva_ajax_get_minicart');

function meziva_ajax_update_minicart() {
    check_ajax_referer('meziva_cart_nonce', 'nonce');

    $key = sanitize_text_field($_POST['cart_item_key'] ?? '');
    $qty = max(0, absint($_POST['quantity'] ?? 0));

    if ($key) {
        WC()->cart->set_quantity($key, $qty, true);
    }

    WC()->cart->calculate_totals();

    wp_send_json_success([
        'html'  => meziva_get_minicart_html(),
        'count' => WC()->cart->get_cart_contents_count(),
    ]);
}
add_action('wp_ajax_meziva_update_minicart', 'meziva_ajax_update_minicart');
add_action('wp_ajax_nopriv_meziva_update_minicart', 'meziva_ajax_update_minicart');

function meziva_ajax_add_to_cart_product() {
    check_ajax_referer('meziva_cart_nonce', 'nonce');

    $product_id = absint($_POST['product_id'] ?? 0);
    $qty = max(1, absint($_POST['quantity'] ?? 1));

    if (!$product_id) {
        wp_send_json_error(['message' => 'Invalid product']);
    }

    $added = WC()->cart->add_to_cart($product_id, $qty);
    WC()->cart->calculate_totals();

    if (!$added) {
        wp_send_json_error(['message' => 'Product not added']);
    }

    wp_send_json_success([
        'html'  => meziva_get_minicart_html(),
        'count' => WC()->cart->get_cart_contents_count(),
    ]);
}
add_action('wp_ajax_meziva_add_to_cart_product', 'meziva_ajax_add_to_cart_product');
add_action('wp_ajax_nopriv_meziva_add_to_cart_product', 'meziva_ajax_add_to_cart_product');