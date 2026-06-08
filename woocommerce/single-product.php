<?php
defined('ABSPATH') || exit;
get_header();
?>

<main class="bg-[#f3eadf] text-[#25190f]">

<?php while (have_posts()) : the_post();

$product = wc_get_product(get_the_ID());
if (!$product || !is_a($product, 'WC_Product')) continue;

$product_id = $product->get_id();
$main_image = get_the_post_thumbnail_url($product_id, 'large') ?: wc_placeholder_img_src();
$gallery_ids = $product->get_gallery_image_ids();
$rating = $product->get_average_rating();
$rating_count = $product->get_rating_count();

$offer_text = function_exists('get_field') ? get_field('pdp_offer_text') : '';
$coupon_text = function_exists('get_field') ? get_field('pdp_coupon_text') : '';
$delivery_text = function_exists('get_field') ? get_field('pdp_delivery_text') : '';
$benefit_image = function_exists('get_field') ? get_field('pdp_benefit_image') : '';
$before_after_image = function_exists('get_field') ? get_field('before_after_image') : '';
$banner_desktop = function_exists('get_field') ? get_field('pdp_banner_image_desktop') : '';
$banner_mobile = function_exists('get_field') ? get_field('pdp_banner_image_mobile') : '';
$banner_url = function_exists('get_field') ? get_field('pdp_banner_url') : '';

$banner_desktop_url = is_array($banner_desktop) ? ($banner_desktop['url'] ?? '') : '';
$banner_mobile_url = is_array($banner_mobile) ? ($banner_mobile['url'] ?? '') : $banner_desktop_url;
?>

<section class="max-w-[1400px] mx-auto px-4 py-6 md:py-10">
    <div class="bg-white rounded-3xl p-4 md:p-6 grid grid-cols-1 md:grid-cols-2 gap-8 shadow-sm">

        <!-- Gallery -->
        <div>
            <div class="relative rounded-3xl overflow-hidden bg-[#f5f5f5] group">
                <?php if ($offer_text) : ?>
                    <div class="absolute top-0 left-0 right-0 z-10 bg-[#8b3f24] text-white text-center font-bold py-2 text-sm">
                        <?php echo esc_html($offer_text); ?>
                    </div>
                <?php endif; ?>

                <img 
                    id="mzMainProductImage"
                    src="<?php echo esc_url($main_image); ?>"
                    class="w-full aspect-square object-cover cursor-zoom-in"
                    alt="<?php echo esc_attr(get_the_title()); ?>"
                >

                <button type="button" id="mzZoomBtn" class="absolute bottom-4 right-4 w-12 h-12 bg-white rounded-full shadow-lg flex items-center justify-center text-lg">
                    🔍
                </button>
            </div>

            <div class="relative mt-4">
                <div id="mzThumbSlider" class="flex gap-3 overflow-x-auto pb-2 no-scrollbar">
                    <button class="mz-thumb shrink-0 border-2 border-[#9fbd58] rounded-xl overflow-hidden" data-img="<?php echo esc_url($main_image); ?>">
                        <img src="<?php echo esc_url($main_image); ?>" class="w-20 h-20 md:w-24 md:h-24 object-cover">
                    </button>

                    <?php foreach ($gallery_ids as $gid) :
                        $img = wp_get_attachment_image_url($gid, 'large');
                        $thumb = wp_get_attachment_image_url($gid, 'thumbnail');
                        if (!$img) continue;
                    ?>
                        <button class="mz-thumb shrink-0 border border-[#ead6c8] rounded-xl overflow-hidden" data-img="<?php echo esc_url($img); ?>">
                            <img src="<?php echo esc_url($thumb); ?>" class="w-20 h-20 md:w-24 md:h-24 object-cover">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Product Info -->
        <div class="md:sticky md:top-28 self-start">
            <h1 class="text-3xl md:text-4xl font-extrabold leading-tight mb-2 text-[#25190f]">
                <?php the_title(); ?>
            </h1>

            <?php if ($product->get_short_description()) : ?>
                <div class="text-sm text-gray-600 mb-3">
                    <?php echo wp_kses_post(apply_filters('woocommerce_short_description', $product->get_short_description())); ?>
                </div>
            <?php endif; ?>

            <div class="flex items-center gap-2 mb-4 text-sm">
                <span class="text-[#c99842]">★</span>
                <strong><?php echo esc_html($rating ?: '0'); ?></strong>
                <span class="text-gray-500">(<?php echo esc_html($rating_count); ?> Ratings)</span>
            </div>

            <div class="text-2xl font-extrabold mb-4 text-[#25190f]">
                <?php echo wp_kses_post($product->get_price_html()); ?>
            </div>

            <?php if ($coupon_text) : ?>
                <div class="bg-[#fff8e8] border border-[#9fbd58] rounded-2xl p-3 mb-4">
                    <div class="text-sm font-extrabold text-[#8b3f24]"><?php echo esc_html($coupon_text); ?></div>
                </div>
            <?php endif; ?>

            <?php if ($delivery_text) : ?>
                <div class="bg-white border border-[#ead6c8] rounded-2xl p-3 mb-4 text-sm text-gray-700">
                    <?php echo esc_html($delivery_text); ?>
                </div>
            <?php endif; ?>

            <div class="text-sm text-gray-600 mb-5">
                <?php if ($product->get_sku()) : ?>
                    <div>SKU: <?php echo esc_html($product->get_sku()); ?></div>
                <?php endif; ?>
                <?php echo wc_get_product_category_list($product_id, ', ', '<div>Category: ', '</div>'); ?>
            </div>

            <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                <div class="flex gap-4 items-center mt-6">
                    <div class="flex items-center border-2 border-[#9fbd58] rounded-2xl overflow-hidden bg-white">
                        <button type="button" class="mz-pdp-qty-minus w-12 h-12 text-xl font-bold">−</button>
                        <input type="number" id="mzPdpQty" value="1" min="1" class="w-14 text-center border-0 outline-none">
                        <button type="button" class="mz-pdp-qty-plus w-12 h-12 text-xl font-bold">+</button>
                    </div>

                    <button 
                        type="button"
                        class="mzAjaxAddCart flex-1 h-14 rounded-2xl bg-[#9fbd58] hover:bg-[#8aa147] text-white font-bold text-lg transition"
                        data-product-id="<?php echo esc_attr($product_id); ?>"
                    >
                        Add To Cart
                    </button>
                </div>
            <?php else : ?>
                <?php woocommerce_template_single_add_to_cart(); ?>
            <?php endif; ?>

            <div class="mt-6 grid grid-cols-2 gap-3 text-sm font-semibold">
                <div>✳ Brightens Skin</div>
                <div>✳ Reduces Dark Spots</div>
                <div>✳ Suitable For All Skin Types</div>
                <div>✳ Treats Hyperpigmentation</div>
            </div>
        </div>
    </div>
</section>

<!-- Popup -->
<div id="mzImagePopup" class="fixed inset-0 bg-black/85 hidden z-[9999] items-center justify-center p-5">
    <button id="mzClosePopup" type="button" class="absolute top-5 right-5 text-white text-5xl leading-none">×</button>

    <button id="mzPopupPrev" type="button" class="absolute left-5 top-1/2 -translate-y-1/2 text-white text-5xl">‹</button>

    <img id="mzPopupImage" src="" class="max-w-[90vw] max-h-[88vh] object-contain rounded-xl">

    <button id="mzPopupNext" type="button" class="absolute right-5 top-1/2 -translate-y-1/2 text-white text-5xl">›</button>
</div>

<?php if ($benefit_image || (function_exists('get_field') && get_field('benefit_title_1'))) : ?>
<section class="max-w-[1400px] mx-auto px-4 py-4">
    <h2 class="text-xl font-extrabold border-b border-[#9fbd58] pb-2 mb-5 text-[#8b3f24]">Benefits</h2>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
        <?php if ($benefit_image) : ?>
            <div class="bg-white rounded-3xl p-5 shadow-sm">
                <img src="<?php echo esc_url($benefit_image['url']); ?>" class="w-full rounded-2xl object-cover" alt="">
            </div>
        <?php endif; ?>

        <div class="bg-white rounded-3xl p-5 shadow-sm">
            <?php for ($i = 1; $i <= 5; $i++) :
                $title = function_exists('get_field') ? get_field("benefit_title_$i") : '';
                $text = function_exists('get_field') ? get_field("benefit_text_$i") : '';
                if (!$title && !$text) continue;
            ?>
                <div class="mz-accordion border-b border-[#ead6c8]">
                    <button class="mz-accordion-btn w-full flex justify-between items-center py-4 font-extrabold text-left text-[#25190f]">
                        <?php echo esc_html($title); ?>
                        <span class="mz-acc-icon w-7 h-7 rounded-full bg-[#f3eadf] text-[#8b3f24] flex items-center justify-center">+</span>
                    </button>
                    <div class="mz-accordion-content hidden pb-4 text-sm leading-6 text-gray-600">
                        <?php echo esc_html($text); ?>
                    </div>
                </div>
            <?php endfor; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<section class="max-w-[1400px] mx-auto px-4 py-4">
    <h2 class="text-xl font-extrabold border-b border-[#9fbd58] pb-2 mb-5 text-[#8b3f24]">Description</h2>
    <div class="bg-white rounded-3xl p-5 leading-8 text-gray-700 shadow-sm">
        <?php the_content(); ?>
    </div>
</section>

<?php if (function_exists('get_field') && get_field('faq_question_1')) : ?>
<section class="max-w-[1400px] mx-auto px-4 py-4">
    <h2 class="text-xl font-extrabold border-b border-[#9fbd58] pb-2 mb-5 text-[#8b3f24]">Honest Answers for Common Questions</h2>

    <div class="bg-white rounded-3xl p-5 shadow-sm">
        <?php for ($i = 1; $i <= 5; $i++) :
            $q = get_field("faq_question_$i");
            $a = get_field("faq_answer_$i");
            if (!$q && !$a) continue;
        ?>
            <div class="mz-accordion border-b border-[#ead6c8]">
                <button class="mz-accordion-btn w-full flex justify-between items-center py-4 font-extrabold text-left text-[#25190f]">
                    <?php echo esc_html($q); ?>
                    <span class="mz-acc-icon w-7 h-7 rounded-full bg-[#f3eadf] text-[#8b3f24] flex items-center justify-center">+</span>
                </button>
                <div class="mz-accordion-content hidden pb-4 text-sm leading-6 text-gray-600">
                    <?php echo esc_html($a); ?>
                </div>
            </div>
        <?php endfor; ?>
    </div>
</section>
<?php endif; ?>

<?php if ($banner_desktop_url) : ?>
<section class="max-w-[1400px] mx-auto px-4 py-6">
    <a href="<?php echo esc_url($banner_url ?: '#'); ?>" class="block rounded-3xl overflow-hidden shadow-sm">
        <picture>
            <source media="(max-width: 767px)" srcset="<?php echo esc_url($banner_mobile_url); ?>">
            <img src="<?php echo esc_url($banner_desktop_url); ?>" class="w-full h-auto block" alt="">
        </picture>
    </a>
</section>
<?php endif; ?>





<?php
$related_ids = wc_get_related_products($product_id, 4);

if (!empty($related_ids)) :
?>
<section class="bg-white py-10 md:py-14">
    <div class="max-w-[1400px] mx-auto px-4 md:px-8">

        <h2 class="text-center text-3xl md:text-4xl font-extrabold text-[#a87800] mb-8">
            Frequently Bought Together
        </h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-5">

            <?php foreach ($related_ids as $related_id) :

                $related_product = wc_get_product($related_id);
                if (!$related_product) continue;

                $main_img = get_the_post_thumbnail_url($related_id, 'large') ?: wc_placeholder_img_src();

                $gallery_ids = $related_product->get_gallery_image_ids();
                $hover_img = !empty($gallery_ids) ? wp_get_attachment_image_url($gallery_ids[0], 'large') : $main_img;

                $regular_price = (float) $related_product->get_regular_price();
                $sale_price = (float) $related_product->get_sale_price();

                $discount = 0;
                if ($regular_price && $sale_price && $regular_price > $sale_price) {
                    $discount = round((($regular_price - $sale_price) / $regular_price) * 100);
                }

                $rating = $related_product->get_average_rating();
                $rating_count = $related_product->get_rating_count();
                $short_desc = wp_strip_all_tags($related_product->get_short_description());
            ?>

                <div class="group">

                    <a href="<?php echo esc_url(get_permalink($related_id)); ?>" class="block relative rounded-xl overflow-hidden bg-[#eaf1f3] aspect-square">
                        <?php if ($related_product->is_on_sale()) : ?>
                            <span class="absolute top-3 left-3 z-10 bg-red-600 text-white text-xs md:text-sm font-bold px-3 py-1 rounded">
                                Limited Time Deal
                            </span>
                        <?php endif; ?>

                        <img
                            src="<?php echo esc_url($main_img); ?>"
                            alt="<?php echo esc_attr($related_product->get_name()); ?>"
                            class="w-full h-full object-cover transition-opacity duration-300 group-hover:opacity-0"
                        >

                        <img
                            src="<?php echo esc_url($hover_img); ?>"
                            alt="<?php echo esc_attr($related_product->get_name()); ?>"
                            class="absolute inset-0 w-full h-full object-cover opacity-0 transition-opacity duration-300 group-hover:opacity-100"
                        >
                    </a>

                    <div class="pt-4">

                        <div class="flex items-center gap-1 text-sm text-gray-700 mb-2">
                            <span class="text-yellow-500">⭐</span>
                            <strong><?php echo esc_html($rating ?: '4.8'); ?></strong>
                            <span>(<?php echo esc_html($rating_count); ?> Ratings)</span>
                        </div>

                        <a href="<?php echo esc_url(get_permalink($related_id)); ?>">
                            <h3 class="font-extrabold text-base md:text-lg text-black leading-snug mb-1">
                                <?php echo esc_html($related_product->get_name()); ?>
                            </h3>
                        </a>

                        <?php if ($short_desc) : ?>
                            <p class="text-sm md:text-base text-gray-600 mb-3 line-clamp-1">
                                <?php echo esc_html($short_desc); ?>
                            </p>
                        <?php endif; ?>

                        <div class="flex items-center gap-2 mb-4">
                            <span class="font-extrabold text-lg md:text-xl text-black">
                                <?php echo wp_kses_post($related_product->get_price_html()); ?>
                            </span>

                            <?php if ($discount > 0) : ?>
                                <span class="bg-[#d8e8c0] text-[#3e6519] text-xs font-extrabold px-2 py-1 rounded">
                                    <?php echo esc_html($discount); ?>% OFF
                                </span>
                            <?php endif; ?>
                        </div>

                        <?php if ($related_product->is_type('simple') && $related_product->is_purchasable() && $related_product->is_in_stock()) : ?>
                            <button
                                type="button"
                                class="meziva-ajax-add-cart w-full bg-[#2f2f2f] hover:bg-[#9fbd58] text-white font-extrabold py-3 rounded-xl transition"
                                data-product-id="<?php echo esc_attr($related_id); ?>"
                            >
                                Add To Cart
                            </button>
                        <?php else : ?>
                            <a
                                href="<?php echo esc_url(get_permalink($related_id)); ?>"
                                class="block text-center w-full bg-[#2f2f2f] hover:bg-[#9fbd58] text-white font-extrabold py-3 rounded-xl transition"
                            >
                                View Product
                            </a>
                        <?php endif; ?>

                    </div>
                </div>

            <?php endforeach; ?>

        </div>

    </div>
</section>
<?php endif; ?>

<?php endwhile; ?>

</main>

<?php get_footer(); ?>