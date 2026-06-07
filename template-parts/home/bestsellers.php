<?php
$title = function_exists('get_field') ? get_field('bestseller_title') : '';
$product_ids_text = function_exists('get_field') ? get_field('bestseller_product_ids') : '';
$enable_slider = function_exists('get_field') ? get_field('bestseller_enable_slider') : false;

$title = $title ?: 'Bestsellers';
$product_ids_text = is_string($product_ids_text) ? $product_ids_text : '';

$product_ids = array_filter(array_map('absint', array_map('trim', explode(',', $product_ids_text))));

$args = [
    'post_type'      => 'product',
    'posts_per_page' => 8,
    'post_status'    => 'publish',
];

if (!empty($product_ids)) {
    $args['post__in'] = $product_ids;
    $args['orderby'] = 'post__in';
} else {
    $args['meta_key'] = 'total_sales';
    $args['orderby'] = 'meta_value_num';
    $args['order'] = 'DESC';
}

$query = new WP_Query($args);
?>

<?php if ($query->have_posts()) : ?>
<section class="py-10 md:py-14 overflow-hidden">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">
        <h2 class="text-center text-3xl md:text-4xl font-extrabold text-[#a87800] mb-8">
            <?php echo esc_html($title ?: 'Bestsellers'); ?>
        </h2>

        <div class="relative">

            <div 
                id="bestsellerSlider"
                class="<?php echo $enable_slider ? 'flex overflow-x-auto scroll-smooth snap-x snap-mandatory gap-5 pb-4 no-scrollbar' : 'grid grid-cols-2 md:grid-cols-4 gap-5'; ?>"
            >

                <?php while ($query->have_posts()) : $query->the_post(); 
                    global $product;

                    if (!$product) continue;

                    $product_id = $product->get_id();
                    $main_img = get_the_post_thumbnail_url($product_id, 'large') ?: wc_placeholder_img_src();

                    $gallery_ids = $product->get_gallery_image_ids();
                    $hover_img = !empty($gallery_ids) ? wp_get_attachment_image_url($gallery_ids[0], 'large') : $main_img;

                    $regular_price = $product->get_regular_price();
                    $sale_price = $product->get_sale_price();

                    $discount = 0;
                    if ($regular_price && $sale_price && $regular_price > $sale_price) {
                        $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                    }

                    $rating = $product->get_average_rating();
                    $rating_count = $product->get_rating_count();

                    $short_desc = wp_strip_all_tags($product->get_short_description());
                ?>

                    <div class="<?php echo $enable_slider ? 'min-w-[48%] md:min-w-[24%] snap-start' : ''; ?> group">
                        <div class="bg-transparent">

                            <a href="<?php the_permalink(); ?>" class="block relative rounded-xl overflow-hidden bg-white aspect-square">
                                <?php if ($product->is_on_sale()) : ?>
                                    <span class="absolute top-3 left-3 z-10 bg-red-600 text-white text-xs md:text-sm font-bold px-3 py-1 rounded">
                                        Limited Time Deal
                                    </span>
                                <?php endif; ?>

                                <img 
                                    src="<?php echo esc_url($main_img); ?>" 
                                    alt="<?php echo esc_attr(get_the_title()); ?>"
                                    class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-0"
                                >

                                <img 
                                    src="<?php echo esc_url($hover_img); ?>" 
                                    alt="<?php echo esc_attr(get_the_title()); ?>"
                                    class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                                >
                            </a>

                            <div class="pt-4">

                                <div class="flex items-center gap-1 text-sm text-gray-700 mb-2">
                                    <span class="text-yellow-500">⭐</span>
                                    <strong><?php echo esc_html($rating ?: '4.8'); ?></strong>
                                    <span>
                                        (<?php echo esc_html($rating_count ?: '0'); ?> Ratings)
                                    </span>
                                </div>

                                <a href="<?php the_permalink(); ?>">
                                    <h3 class="font-extrabold text-base md:text-lg text-black leading-snug mb-1">
                                        <?php the_title(); ?>
                                    </h3>
                                </a>

                                <?php if ($short_desc) : ?>
                                    <p class="text-sm md:text-base text-gray-600 mb-3 line-clamp-1">
                                        <?php echo esc_html($short_desc); ?>
                                    </p>
                                <?php endif; ?>

                                <div class="flex items-center gap-2 mb-4">
                                    <span class="font-extrabold text-xl text-black">
                                        <?php echo wp_kses_post($product->get_price_html()); ?>
                                    </span>

                                    <?php if ($discount > 0) : ?>
                                        <span class="bg-[#d8e8c0] text-[#3e6519] text-xs font-extrabold px-2 py-1 rounded">
                                            <?php echo esc_html($discount); ?>% OFF
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <button 
                                    class="meziva-add-to-cart w-full bg-[#333333] cursor-pointer hover:bg-[#222222] text-white font-extrabold py-3 rounded-xl transition"
                                    data-product-id="<?php echo esc_attr($product_id); ?>"
                                >
                                    Add To Cart
                                </button>

                            </div>
                        </div>
                    </div>

                <?php endwhile; wp_reset_postdata(); ?>

            </div>

            <?php if ($enable_slider) : ?>
                <button id="bestsellerPrev" class="hidden md:flex absolute left-0 top-1/2 -translate-y-1/2 -translate-x-5 w-10 h-10 bg-white shadow rounded-full items-center justify-center text-2xl">
                    ‹
                </button>

                <button id="bestsellerNext" class="hidden md:flex absolute right-0 top-1/2 -translate-y-1/2 translate-x-5 w-10 h-10 bg-white shadow rounded-full items-center justify-center text-2xl">
                    ›
                </button>
            <?php endif; ?>

        </div>
    </div>
</section>
<?php endif; ?>