<?php
$logo_id = get_theme_mod('custom_logo');
$logo = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : get_template_directory_uri() . '/assets/images/logo.png';

$about_text  = meziva_get_option('meziva_footer_about_text', 'Meziva Beauty brings simple, effective and skin-friendly products made for everyday care.');
$menu_title   = meziva_get_option('meziva_footer_menu_title', 'Quick Links');
$social_title = meziva_get_option('meziva_footer_social_title', 'Follow us on');

$email = meziva_get_option('meziva_footer_email', 'contact@meziva.in');
$phone = meziva_get_option('meziva_footer_phone', '+91 85806 24176');
$copyright = meziva_get_option('meziva_footer_copyright', 'All rights reserved.');

$social_links = [
    ['url' => meziva_get_option('meziva_social_facebook', ''), 'icon' => 'f'],
    ['url' => meziva_get_option('meziva_social_twitter', ''), 'icon' => '𝕏'],
    ['url' => meziva_get_option('meziva_social_instagram', ''), 'icon' => '◎'],
    ['url' => meziva_get_option('meziva_social_pinterest', ''), 'icon' => 'p'],
    ['url' => meziva_get_option('meziva_social_youtube', ''), 'icon' => '▶'],
];
?>

<footer class="bg-[#f5f5f5] text-black">

    <div class="max-w-[1400px] mx-auto px-4 md:px-8 py-10 grid grid-cols-1 md:grid-cols-4 gap-10">

        <div>
            <a href="<?php echo esc_url(home_url('/')); ?>" class="inline-block mb-4">
                <img src="<?php echo esc_url($logo); ?>" alt="<?php bloginfo('name'); ?>" class="h-14 object-contain">
            </a>

            <?php if ($about_text) : ?>
                <p class="text-sm md:text-base leading-7 text-gray-700 max-w-xl">
                    <?php echo esc_html($about_text); ?>
                </p>
            <?php endif; ?>
        </div>

        <div class="md:col-span-2 lg:px-[80px]">
            <?php if ($menu_title) : ?>
                <h3 class="text-lg font-extrabold mb-4 text-[#7a3f1c]">
                    <?php echo esc_html($menu_title); ?>
                </h3>
            <?php endif; ?>

            <nav class="meziva-footer-menu text-gray-700 font-medium">
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'container'      => false,
                    'items_wrap'     => '<ul class="grid grid-cols-2 gap-x-10 gap-y-3 list-none p-0 m-0">%3$s</ul>',
                    'fallback_cb'    => false,
                    'depth'          => 1,
                ]);
                ?>
            </nav>
        </div>

        <div>
            <?php if ($social_title) : ?>
                <h3 class="text-lg font-extrabold mb-4 text-[#7a3f1c]">
                    <?php echo esc_html($social_title); ?>
                </h3>
            <?php endif; ?>

            <div class="flex items-center gap-3 mb-5">
                <?php foreach ($social_links as $social) : ?>
                    <?php if (!empty($social['url'])) : ?>
                        <a href="<?php echo esc_url($social['url']); ?>" target="_blank" rel="noopener noreferrer" class="w-10 h-10 rounded-full bg-[#eaf1d8] hover:bg-[#dbe8c2] flex items-center justify-center font-bold transition">
                            <?php echo esc_html($social['icon']); ?>
                        </a>
                    <?php endif; ?>
                <?php endforeach; ?>
            </div>

            <?php if ($phone) : ?>
                <p class="text-sm text-gray-700 mb-2">
                    Phone:
                    <a href="tel:<?php echo esc_attr(preg_replace('/\s+/', '', $phone)); ?>" class="font-bold text-[#7a3f1c]">
                        <?php echo esc_html($phone); ?>
                    </a>
                </p>
            <?php endif; ?>

            <?php if ($email) : ?>
                <p class="text-sm text-gray-700">
                    Email:
                    <a href="mailto:<?php echo esc_attr($email); ?>" class="font-bold text-[#7a3f1c]">
                        <?php echo esc_html($email); ?>
                    </a>
                </p>
            <?php endif; ?>
        </div>

    </div>

    <div class="border-t border-[#dbe8c2] py-4 text-center text-sm text-gray-600 px-4">
        © <?php echo esc_html(date('Y')); ?> <?php bloginfo('name'); ?>. <?php echo esc_html($copyright); ?>
    </div>

</footer>

<?php wp_footer(); ?>
</body>
</html>