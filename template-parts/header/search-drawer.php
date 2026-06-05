<div id="searchOverlay" class="fixed inset-0 bg-black/55 z-[70] hidden"></div>

<div id="searchDrawer" class="fixed top-2 right-2 md:right-20 w-[96%] md:w-[620px] max-h-[96vh] overflow-y-auto bg-white z-[80] rounded-2xl border border-[#7a3f1c] p-6 hidden">

  <div class="flex items-center gap-3 border-b-2 border-black pb-3">
    <input 
      id="searchInput"
      type="text"
      placeholder="Search for..."
      class="flex-1 outline-none text-xl font-bold"
      autocomplete="off"
    >

    <button id="searchClear" class="text-sm hidden">Clear</button>
    <button id="searchClose" class="text-3xl leading-none">×</button>
  </div>

  <div id="trendingSearch" class="pt-6">
    <h4 class="font-bold text-gray-700 mb-4">Trending Products | Search</h4>

    <div class="space-y-3 text-xl font-extrabold">
      <p>Tinted Lip Balms</p>
      <p>Sunscreen SPF 50 PA++++ 🌞</p>
      <p>Better Ageing Serum</p>
      <p>Milk Powder Face Wash</p>
      <p>Shampoo</p>
      <p>Body Lotion</p>
      <p>Best Sellers</p>
      <p>Aloe Vera Gel and Combos</p>
      <p>Lip Balm</p>
      <p>Eye Cream</p>
      <p>Hair serum</p>
      <p>Face Mask</p>
    </div>
  </div>

  <div id="searchResultsWrap" class="hidden pt-6">
    <div class="flex gap-6 text-xl font-bold mb-6 overflow-x-auto">
      <button class="text-black">Products</button>
      <button class="text-gray-400">Blog posts</button>
      <button class="text-gray-400">Collections</button>
      <button class="text-gray-400">Pages</button>
    </div>

    <div id="searchResults" class="space-y-5"></div>
  </div>

</div>