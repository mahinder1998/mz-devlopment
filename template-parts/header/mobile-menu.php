<div id="mobileOverlay" class="fixed inset-0 bg-black/50 z-50 hidden"></div>

<aside id="mobileMenu" class="fixed top-0 left-0 w-[86%] max-w-[340px] h-full bg-white z-[60] -translate-x-full transition-transform duration-300 overflow-y-auto">

  <div class="flex items-center justify-between p-5 border-b">
    <div class="flex items-center gap-4">
      <div class="w-12 h-12 rounded-full border flex items-center justify-center text-xl">🏃</div>
      <strong>Welcome, <span class="text-[#93aa52] font-normal">Tribe</span></strong>
    </div>

    <button id="mobileMenuClose" class="w-9 h-9 rounded-full bg-gray-100 text-2xl">×</button>
  </div>

  <div class="p-3">
    <button class="w-full border-2 border-[#9ab25b] py-3 flex justify-center items-center gap-3">
      🧾 Track Order
    </button>
  </div>

  <img 
    src="<?php echo get_template_directory_uri(); ?>/assets/menu-banner.jpg" 
    class="w-full h-[55px] object-cover"
    alt=""
  >

  <div id="mobileMainList" class="p-5 space-y-0">
    <a class="block border-b border-[#9ab25b] py-3 text-lg">Bestsellers</a>
    <a class="block border-b border-[#9ab25b] py-3 text-lg">New Launch ✨ 🧴 <span class="bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span></a>
    <a class="inline-block bg-[#9ab25b] text-white rounded-lg px-3 py-2 my-3 text-lg">Product of the Month</a>
    <a class="block border-b border-[#9ab25b] py-3 text-lg">Shop All</a>

    <button class="mobileSubOpen w-full border-b border-[#9ab25b] py-3 text-lg flex justify-between" data-title="Hair">
      Hair <span>›</span>
    </button>

    <button class="mobileSubOpen w-full border-b border-[#9ab25b] py-3 text-lg flex justify-between" data-title="Face">
      Face <span>›</span>
    </button>

    <button class="mobileSubOpen w-full border-b border-[#9ab25b] py-3 text-lg flex justify-between" data-title="Body">
      Body <span>›</span>
    </button>

    <button class="mobileSubOpen w-full border-b border-[#9ab25b] py-3 text-lg flex justify-between" data-title="Milk Range">
      Milk Range <span>›</span>
    </button>

    <button class="mobileSubOpen w-full border-b border-[#9ab25b] py-3 text-lg flex justify-between" data-title="Combos & Gifting">
      Combos & Gifting <span class="bg-red-800 text-white text-xs px-2 py-1 rounded-full">New</span> <span>›</span>
    </button>
  </div>

  <div id="mobileSubMenu" class="hidden p-5">
    <div class="flex items-center gap-4 border-b border-[#9ab25b] pb-3 mb-6">
      <button id="mobileSubBack" class="text-2xl">←</button>
      <span class="w-px h-8 bg-[#9ab25b]"></span>
      <h3 id="mobileSubTitle" class="text-2xl font-medium">Hair</h3>
    </div>

    <div class="space-y-4 text-base">
      <a class="block">View all</a>
      <a class="block">Hair Regrowth Oil</a>
      <a class="block">Goat Milk Shampoo</a>
      <a class="block">Anti-Frizz Hair Serum</a>
      <a class="block">Goat Milk Hair Mask</a>
    </div>
  </div>

  <div class="fixed left-4 bottom-24 flex flex-col gap-4">
    <a class="w-12 h-12 bg-[#9ab25b] rounded-md flex items-center justify-center text-white text-2xl">☏</a>
    <a class="w-14 h-14 bg-[#9ab25b] rounded-full flex items-center justify-center text-white text-2xl shadow-lg">🎁</a>
  </div>
</aside>