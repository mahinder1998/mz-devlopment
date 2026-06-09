<?php
$mobile_banner = meziva_get_option('meziva_mobile_banner', get_template_directory_uri() . '/assets/images/menu-banner.jpg');
$track_order_url = meziva_get_option('meziva_track_order_url', home_url('/track-order'));
$whatsapp_url = meziva_get_option('meziva_whatsapp_url', 'https://wa.me/');
?>
<div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-50 hidden"></div>
<aside id="mobileMenu" class="fixed top-0 left-0 w-[86%] max-w-[340px] h-full bg-white z-[60] -translate-x-full transition-transform duration-300 overflow-y-auto">
  <div class="flex items-center justify-between p-5 border-b">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-full border flex items-center justify-center text-xl">🏃</div>
      <strong>
    Welcome,
    <span class="text-primary font-normal">
        <?php
        if ( is_user_logged_in() ) {
            $current_user = wp_get_current_user();

            echo esc_html(
                !empty($current_user->first_name)
                    ? $current_user->first_name
                    : $current_user->display_name
            );
        } else {
            echo 'Tribe';
        }
        ?>
    </span>
</strong>
    </div>
    <button id="mobileMenuClose" class="w-9 h-9 rounded-full bg-gray-100 text-2xl" aria-label="Close menu">×</button>
  </div>


  <!-- <img src="<?php echo esc_url($mobile_banner); ?>" class="w-full h-[55px] object-cover" alt=""> -->

  <div id="mobileMainList" class="p-5 space-y-0">
    <?php wp_nav_menu([
      'theme_location' => 'mobile',
      'container' => false,
      'items_wrap' => '%3$s',
      'fallback_cb' => false,
      'walker' => new Meziva_Mobile_Menu_Walker(),
    ]); ?>
  </div>

  <div id="mobileSubMenu" class="hidden p-5">
    <div class="flex items-center gap-4 border-b border-[#9ab25b] pb-3 mb-6">
      <button id="mobileSubBack" class="text-2xl" aria-label="Back">←</button>
      <span class="w-px h-8 bg-[#9ab25b]"></span>
      <h3 id="mobileSubTitle" class="text-2xl font-medium"></h3>
    </div>
    <div id="mobileSubItems" class="space-y-4 text-base"></div>
  </div>


</aside>
