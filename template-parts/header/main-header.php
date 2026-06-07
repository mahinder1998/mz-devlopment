<?php
$logo_id = get_theme_mod('custom_logo');
$logo = $logo_id ? wp_get_attachment_image_url($logo_id, 'full') : get_template_directory_uri() . '/assets/images/logo.png';
$cart_count = function_exists('WC') && WC()->cart ? WC()->cart->get_cart_contents_count() : 0;
?>
<header class="bg-white sticky top-0 z-40 border-b border-gray-100">
  <div class="max-w-[1400px] mx-auto px-4 md:px-8 h-[74px] flex items-center justify-between">
    <div class="flex gap-4 md:hidden">
      <button id="mobileMenuOpen" class="md:hidden" aria-label="Open menu">
    <svg class="w-6 h-6 text-gray-800 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
    <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="M5 7h14M5 12h14M5 17h14"/>
    </svg>
    </button>

      <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account')); ?>" class="text-xl  md:hidden">
      <svg class="w-6 h-6 text-gray-800 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
      <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
      </svg>
    </div>



    <a href="<?php echo esc_url(home_url('/')); ?>" class="flex items-center">
      <img src="<?php echo esc_url($logo); ?>" class="h-12 md:h-16 object-contain" alt="<?php echo esc_attr(get_bloginfo('name')); ?>">
    </a>

    <nav class="hidden md:flex items-center gap-7 text-base">
      <?php wp_nav_menu([
        'theme_location' => 'primary',
        'container' => false,
        'items_wrap' => '%3$s',
        'fallback_cb' => false,
        'walker' => new Meziva_Desktop_Menu_Walker(),
      ]); ?>
    </nav>

    <div class="flex items-center gap-4">
      <button id="searchOpen" class="text-2xl" aria-label="Open search">
        <svg class="w-6 h-6 text-gray-800 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
        </svg>

      </button>
      <a href="<?php echo esc_url(function_exists('wc_get_page_permalink') ? wc_get_page_permalink('myaccount') : home_url('/my-account')); ?>" class="text-xl hidden md:flex">
      <svg class="w-6 h-6 text-gray-800 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 24 24">
      <path fill-rule="evenodd" d="M12 20a7.966 7.966 0 0 1-5.002-1.756l.002.001v-.683c0-1.794 1.492-3.25 3.333-3.25h3.334c1.84 0 3.333 1.456 3.333 3.25v.683A7.966 7.966 0 0 1 12 20ZM2 12C2 6.477 6.477 2 12 2s10 4.477 10 10c0 5.5-4.44 9.963-9.932 10h-.138C6.438 21.962 2 17.5 2 12Zm10-5c-1.84 0-3.333 1.455-3.333 3.25S10.159 13.5 12 13.5c1.84 0 3.333-1.455 3.333-3.25S13.841 7 12 7Z" clip-rule="evenodd"/>
      </svg>

      </a>
      <a href="<?php echo esc_url(function_exists('wc_get_cart_url') ? wc_get_cart_url() : home_url('/cart')); ?>" class="relative text-xl">
        <svg class="w-6 h-6 text-gray-800 " aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 4h1.5L9 16m0 0h8m-8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm8 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4Zm-8.5-3h9.25L19 7H7.312"/>
        </svg>

        <span class="absolute -top-2 -right-2 text-xs bg-[#7a3f1c] text-white border rounded-full px-1"><?php echo esc_html($cart_count); ?></span>
      </a>
    </div>
  </div>
</header>
