<header class="bg-white sticky top-0 z-40 border-b border-gray-100">
  <div class="max-w-[1400px] mx-auto px-4 md:px-8 h-[74px] flex items-center justify-between">

    <button id="mobileMenuOpen" class="md:hidden">
      <span class="block w-7 h-[2px] bg-black mb-2"></span>
      <span class="block w-7 h-[2px] bg-black mb-2"></span>
      <span class="block w-7 h-[2px] bg-black"></span>
    </button>

    <a href="<?php echo home_url(); ?>" class="flex items-center">
      <img 
        src="<?php echo get_template_directory_uri(); ?>/assets/logo.png" 
        class="h-12 md:h-16 object-contain"
        alt="Logo"
      >
    </a>

    <nav class="hidden md:flex items-center gap-7 text-base">
      <a href="#" class="hover:text-[#93aa52]">Bestsellers</a>

      <button class="megaTrigger relative hover:text-[#93aa52]" data-menu="hair">
        Hair <span>⌄</span>
      </button>

      <button class="megaTrigger relative hover:text-[#93aa52]" data-menu="face">
        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>
        Face <span>⌄</span>
      </button>

      <button class="megaTrigger hover:text-[#93aa52]" data-menu="body">
        Body <span>⌄</span>
      </button>

      <button class="megaTrigger hover:text-[#93aa52]" data-menu="milk">
        Milk Range <span>⌄</span>
      </button>

      <a href="#" class="relative hover:text-[#93aa52]">
        <span class="absolute -top-8 left-1/2 -translate-x-1/2 bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span>
        Combos
      </a>

      <a href="#">Shop All</a>
      <a href="#">About us</a>
      <a href="#">Blog</a>
    </nav>

    <div class="flex items-center gap-4">
      <button id="searchOpen" class="text-2xl">⌕</button>
      <span class="text-xl">🏃</span>
<!--       
      <a href="<?php // echo wc_get_cart_url(); ?>" class="relative text-xl">
        🛍️
        <span class="absolute -top-2 -right-2 text-xs bg-[#a2bb5e] border rounded-full px-1">
          <?php // echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?>
        </span>
      </a> -->
    </div>
  </div>
</header>