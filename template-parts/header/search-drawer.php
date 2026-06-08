<?php
$search_heading = meziva_get_option('meziva_search_heading', 'Trending Products | Search');

$trending_items = [];

for ($i = 1; $i <= 12; $i++) {
    $item = meziva_get_option("meziva_trending_search_{$i}", '');
    if ($item) {
        $trending_items[] = $item;
    }
}
?>

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
        <?php if ($search_heading) : ?>
            <h4 class="font-bold text-gray-700 mb-4">
                <?php echo esc_html($search_heading); ?>
            </h4>
        <?php endif; ?>

        <?php if (!empty($trending_items)) : ?>
            <div class="space-y-3 text-xl font-extrabold">
                <?php foreach ($trending_items as $item) : ?>
                    <button type="button" class="meziva-trending-item block text-left hover:text-[#9fbd58]" data-keyword="<?php echo esc_attr($item); ?>">
                        <?php echo esc_html($item); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <div id="searchResultsWrap" class="hidden pt-6">
        <div class="flex gap-6 text-lg md:text-xl font-bold mb-6 overflow-x-auto">
            <button type="button" class="meziva-search-tab text-black" data-tab="products">Products</button>
            <button type="button" class="meziva-search-tab text-gray-400" data-tab="posts">Blog posts</button>
            <button type="button" class="meziva-search-tab text-gray-400" data-tab="collections">Collections</button>
            <button type="button" class="meziva-search-tab text-gray-400" data-tab="pages">Pages</button>
        </div>

        <div id="searchResults" class="space-y-5"></div>
    </div>

</div>