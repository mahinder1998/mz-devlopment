<?php
$banner = meziva_get_option('meziva_mega_banner', get_template_directory_uri() . '/assets/images/mega-banner.jpg');
$menu_json = meziva_get_menu_json('primary');
?>
<div id="megaMenu" class="hidden fixed left-0 right-0 top-[138px] z-30 bg-white shadow-xl border-t">
  <div class="max-w-[1400px] mx-auto grid grid-cols-3 gap-12 p-10 rounded-b-2xl">
    <div>
      <h3 id="megaColOneTitle" class="font-bold text-xl underline mb-5 text-gray-600">Bestsellers</h3>
      <ul id="megaColOne" class="space-y-4 text-lg"></ul>
    </div>
    <div class="border-l pl-16">
      <h3 class="font-bold text-xl underline mb-5 text-gray-600">Shop by product</h3>
      <ul id="megaColTwo" class="space-y-4 text-lg"></ul>
    </div>
    <div>
      <img src="<?php echo esc_url($banner); ?>" class="w-full h-[305px] object-cover rounded-lg" alt="">
    </div>
  </div>
</div>
<script>window.MEZIVA_MENU_ITEMS = <?php echo wp_json_encode($menu_json); ?>;</script>
