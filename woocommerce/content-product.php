<?php
defined('ABSPATH') || exit;

global $product;

if (!$product || !$product->is_visible()) {
    return;
}

$product_id = $product->get_id();
$image_id = $product->get_image_id();
$gallery_ids = $product->get_gallery_image_ids();

$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'large') : wc_placeholder_img_src();
$hover_url = !empty($gallery_ids[0]) ? wp_get_attachment_image_url($gallery_ids[0], 'large') : $image_url;

$rating_count = $product->get_rating_count();
$average = $product->get_average_rating();

$regular_price = (float) $product->get_regular_price();
$sale_price = (float) $product->get_sale_price();

$discount = 0;
if ($regular_price > 0 && $sale_price > 0 && $regular_price > $sale_price) {
    $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
}
?>

<div <?php wc_product_class('meziva-shop-card group', $product); ?>>

    <a href="<?php echo esc_url(get_permalink($product_id)); ?>" class="block">

        <div class="relative bg-[#eaf2f4] rounded-2xl overflow-hidden aspect-square">

            <span class="absolute top-3 left-3 z-10 bg-red-600 text-white text-sm font-bold px-3 py-2 rounded">
                Limited Time Deal
            </span>

            <?php if ($discount > 0) : ?>
                <span class="absolute bottom-0 right-0 z-10 bg-primary text-white text-sm font-bold px-3 py-2 rounded-tl-lg">
                    <?php echo esc_html($discount); ?>% OFF
                </span>
            <?php endif; ?>

            <img 
                src="<?php echo esc_url($image_url); ?>" 
                alt="<?php echo esc_attr(get_the_title()); ?>"
                class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-0"
            >

            <img 
                src="<?php echo esc_url($hover_url); ?>" 
                alt="<?php echo esc_attr(get_the_title()); ?>"
                class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-300 group-hover:opacity-100"
            >

        </div>

        <div class="pt-4">

            <div class="text-sm text-gray-700 mb-2">
                <span class="text-yellow-400">★</span>
                <strong><?php echo esc_html($average ?: '4.8'); ?></strong>
                <span>(<?php echo esc_html($rating_count); ?> Ratings)</span>
            </div>

            <h3 class="text-lg font-extrabold text-black mb-2 line-clamp-1">
                <?php echo esc_html(get_the_title()); ?>
            </h3>

            <?php if ($product->get_short_description()) : ?>
                <p class="text-gray-600 text-base mb-3 line-clamp-1">
                    <?php echo esc_html(wp_strip_all_tags($product->get_short_description())); ?>
                </p>
            <?php endif; ?>

            <div class="text-xl font-extrabold text-black mb-4">
                <?php echo wp_kses_post($product->get_price_html()); ?>
            </div>

        </div>
    </a>

    <button 
        type="button"
        class="meziva-ajax-add-cart w-full h-12 rounded-xl bg-secondary hover:bg-secondaryLight text-white font-extrabold transition disabled:opacity-60"
        data-product-id="<?php echo esc_attr($product_id); ?>"
    >
        <?php echo $product->is_type('simple') ? 'Add To Cart' : 'Select options'; ?>
    </button>

</div>