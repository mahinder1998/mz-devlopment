<?php
defined('ABSPATH') || exit;
get_header();

function mz_img_url($img, $size = 'large') {
    if (is_array($img) && !empty($img['url'])) return $img['url'];
    if (is_numeric($img)) return wp_get_attachment_image_url($img, $size);
    return '';
}

function mz_field($key, $default = '') {
    return function_exists('get_field') ? (get_field($key) ?: $default) : $default;
}
?>

<main class="bg-[#f3eadf] text-heading pb-24 md:pb-0">

<?php while (have_posts()) : the_post();

$product = wc_get_product(get_the_ID());
if (!$product) continue;

$product_id   = $product->get_id();
$main_image   = get_the_post_thumbnail_url($product_id, 'large') ?: wc_placeholder_img_src();
$gallery_ids  = $product->get_gallery_image_ids();
$rating       = $product->get_average_rating();
$rating_count = $product->get_rating_count();

$coupon_code  = mz_field('pdp_coupon_code', 'HURRY20');
$coupon_text  = mz_field('pdp_coupon_text', 'Flat 20% off');
$offer_text   = mz_field('pdp_offer_text', 'FLAT 20% off | Use Code: HURRY20');

$desc_text    = $product->get_description();
$short_desc   = $product->get_short_description();

$banner_desktop = mz_img_url(mz_field('pdp_banner_desktop'));
$banner_mobile  = mz_img_url(mz_field('pdp_banner_mobile')) ?: $banner_desktop;
$banner_url     = mz_field('pdp_banner_url', '#');

$usage_image = mz_img_url(mz_field('pdp_usage_image'));
$before_after_image = mz_img_url(mz_field('pdp_before_after_image'));
$benefit_image = mz_img_url(mz_field('pdp_benefit_image'));

$gallery_images = [$main_image];

foreach ($gallery_ids as $gid) {
    $img = wp_get_attachment_image_url($gid, 'large');
    if ($img) $gallery_images[] = $img;
}
?>

<section class="max-w-[1400px] mx-auto px-3 md:px-4 pt-4 md:pt-8">

    <div class="text-xs text-gray-600 mb-3 hidden md:block">
        <a href="<?php echo esc_url(home_url('/')); ?>">Home</a> /
        <?php echo wc_get_product_category_list($product_id, ', '); ?> /
        <span><?php the_title(); ?></span>
    </div>

    <div class="bg-white rounded-[22px] md:rounded-[28px] p-3 md:p-7 grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-10 shadow-sm">

        <!-- Gallery -->
        <div>
            <div class="relative rounded-[22px] overflow-hidden bg-[#eef5f1]">
                <?php if ($offer_text) : ?>
                    <div class="absolute top-0 left-0 right-0 z-10 bg-primary text-white text-center font-extrabold py-2 text-xs md:text-sm">
                        <?php echo esc_html($offer_text); ?>
                    </div>
                <?php endif; ?>

                <img
                    id="mzMainProductImage"
                    src="<?php echo esc_url($main_image); ?>"
                    alt="<?php echo esc_attr(get_the_title()); ?>"
                    class="w-full aspect-square object-cover cursor-zoom-in"
                >

                <button type="button" id="mzZoomBtn" class="absolute bottom-4 right-4 w-11 h-11 rounded-full bg-white shadow-lg flex items-center justify-center text-lg">
                    <svg class="w-6 h-6 text-gray-800 dark:text-white" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24">
                        <path stroke="currentColor" stroke-linecap="round" stroke-width="2" d="m21 21-3.5-3.5M17 10a7 7 0 1 1-14 0 7 7 0 0 1 14 0Z"/>
                        </svg>

                </button>
            </div>

            <div class="mt-4 overflow-hidden">
                <div id="mzThumbSlider" class="flex gap-3 overflow-x-auto no-scrollbar pb-2">
                    <?php foreach ($gallery_images as $index => $img) : ?>
                        <button
                            type="button"
                            class="mz-thumb shrink-0 rounded-xl overflow-hidden border-2 <?php echo $index === 0 ? 'border-primary' : 'border-transparent'; ?>"
                            data-index="<?php echo esc_attr($index); ?>"
                            data-img="<?php echo esc_url($img); ?>"
                        >
                            <img src="<?php echo esc_url($img); ?>" class="w-20 h-20 md:w-24 md:h-24 object-cover">
                        </button>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

        <!-- Info -->
        <div class="md:sticky md:top-28 self-start">
            <h1 class="text-2xl md:text-4xl font-extrabold leading-tight mb-2">
                <?php the_title(); ?>
            </h1>

            <?php if ($short_desc) : ?>
                <div class="text-sm text-gray-600 mb-3 leading-6">
                    <?php echo wp_kses_post($short_desc); ?>
                </div>
            <?php endif; ?>

            <div class="flex items-center gap-2 text-sm mb-4">
                <span class="text-yellow-400">★</span>
                <strong><?php echo esc_html($rating ?: '0'); ?></strong>
                <span class="text-gray-500">(<?php echo esc_html($rating_count); ?> Ratings)</span>
            </div>

            <div class="text-2xl md:text-3xl font-extrabold mb-4">
                <?php echo wp_kses_post($product->get_price_html()); ?>
            </div>

            <?php if ($coupon_code) : ?>
                <div class="mb-3">
                    <button
                        type="button"
                        class="mz-copy-coupon w-full md:w-auto border border-dashed border-primary rounded-xl bg-[#fff9eb] px-4 py-2 text-sm font-bold text-secondary"
                        data-coupon="<?php echo esc_attr($coupon_code); ?>"
                    >
                        Coupon Code: <span><?php echo esc_html($coupon_code); ?></span>
                    </button>
                    <p id="mzCouponMsg" class="hidden text-xs text-primary font-bold mt-1">Coupon copied!</p>
                </div>
            <?php endif; ?>

            <?php if ($coupon_text) : ?>
                <div class="border border-primary rounded-xl overflow-hidden mb-4">
                    <div class="grid grid-cols-2">
                        <div class="p-3 font-bold">
                            <?php echo esc_html($product->get_attribute('pa_size') ?: 'Special Offer'); ?>
                            <div class="text-lg"><?php echo wp_kses_post($product->get_price_html()); ?></div>
                        </div>
                        <div class="bg-[#f1f6df] p-3 text-sm font-bold text-center flex items-center justify-center">
                            <?php echo esc_html($coupon_text); ?>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <div class="text-sm leading-7 text-gray-700 mb-5">
                <?php echo wp_kses_post(wp_trim_words($desc_text ?: $short_desc, 70)); ?>
            </div>

            <div class="grid grid-cols-2 gap-3 text-sm font-bold mb-5">
                <?php for ($i = 1; $i <= 4; $i++) :
                    $benefit = mz_field("pdp_key_benefit_$i");
                    if (!$benefit) {
                        $defaults = ['Brightens Skin', 'Reduces Dark Spots', 'Suitable For All Skin Types', 'Treats Hyperpigmentation'];
                        $benefit = $defaults[$i - 1] ?? '';
                    }
                ?>
                    <div class="flex gap-2 items-center">
                        <span class="text-primary">✳</span>
                        <span><?php echo esc_html($benefit); ?></span>
                    </div>
                <?php endfor; ?>
            </div>

            <?php if ($product->is_type('simple') && $product->is_purchasable() && $product->is_in_stock()) : ?>
                <div class="hidden md:flex gap-4 items-center">
                    <div class="flex items-center border-2 border-primary rounded-xl overflow-hidden bg-white">
                        <button type="button" class="mz-pdp-qty-minus w-12 h-12 text-xl font-bold">−</button>
                        <input type="number" id="mzPdpQty" value="1" min="1" class="w-14 text-center border-0 outline-none">
                        <button type="button" class="mz-pdp-qty-plus w-12 h-12 text-xl font-bold">+</button>
                    </div>

                    <button
                        type="button"
                        class="mzAjaxAddCart flex-1 h-14 rounded-xl bg-primary hover:bg-primary-light text-white font-extrabold text-lg transition"
                        data-product-id="<?php echo esc_attr($product_id); ?>"
                    >
                        Add To Cart
                    </button>
                </div>
            <?php else : ?>
                <div class="hidden md:block">
                    <?php woocommerce_template_single_add_to_cart(); ?>
                </div>
            <?php endif; ?>

            <div class="mt-5 flex items-center justify-end gap-2 text-sm font-bold text-gray-600">
                📦 <span>Shipping & Returns</span>
            </div>
        </div>
    </div>
</section>

<!-- Image Popup -->
<div id="mzImagePopup" class="fixed inset-0 bg-black/90 hidden z-[99999] items-center justify-center p-4">
    <button id="mzClosePopup" type="button" class="absolute top-5 right-5 text-white text-5xl">×</button>
    <button id="mzPopupPrev" type="button" class="absolute left-4 top-1/2 -translate-y-1/2 text-white text-5xl">‹</button>
    <img id="mzPopupImage" src="" class="max-w-[92vw] max-h-[88vh] object-contain rounded-xl">
    <button id="mzPopupNext" type="button" class="absolute right-4 top-1/2 -translate-y-1/2 text-white text-5xl">›</button>
</div>

<!-- Accordions -->
<section class="max-w-[1400px] mx-auto px-3 md:px-4 py-5">


 <div class="mz-pdp-section border-b border-primary py-3">
        <button type="button" class="mz-section-toggle w-full flex items-center justify-between font-extrabold text-left">
            <span>Description</span>
            <span class="w-6 h-6 rounded-full border border-primary flex items-center justify-center text-sm">+</span>
        </button>
        <div class="mz-section-content hidden pt-4">
            <div class="bg-white rounded-2xl p-5 shadow-sm text-sm md:text-base leading-8 text-gray-700">
                <?php the_content(); ?>
            </div>
        </div>
    </div>

    <?php
    $sections = [
        'Benefits' => [
            'image' => $benefit_image,
            'fields' => 'benefit',
        ],
        'Ingredients' => [
            'image' => mz_img_url(mz_field('pdp_ingredients_image')),
            'fields' => 'ingredient',
        ],
        'Before/After' => [
            'image' => $before_after_image,
            'fields' => '',
        ],
        'Usage' => [
            'image' => $usage_image,
            'fields' => 'usage',
        ],
    ];

    foreach ($sections as $section_title => $section_data) :
        $image = $section_data['image'];
        $prefix = $section_data['fields'];
    ?>
        <div class="mz-pdp-section border-b border-primary py-3">
            <button type="button" class="mz-section-toggle w-full flex items-center justify-between font-extrabold text-left">
                <span><?php echo esc_html($section_title); ?></span>
                <span class="w-6 h-6 rounded-full border border-primary flex items-center justify-center text-sm">+</span>
            </button>

            <div class="mz-section-content hidden pt-4">
                <?php if ($image) : ?>
                    <div class="max-w-[520px] mx-auto mb-4">
                        <img src="<?php echo esc_url($image); ?>" class="w-full rounded-2xl shadow-sm">
                    </div>
                <?php endif; ?>

                <?php if ($prefix) : ?>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php for ($i = 1; $i <= 6; $i++) :
                            $title = mz_field("pdp_{$prefix}_title_$i");
                            $text  = mz_field("pdp_{$prefix}_text_$i");
                            if (!$title && !$text) continue;
                        ?>
                            <div class="bg-white rounded-2xl p-4 shadow-sm">
                                <?php if ($title) : ?>
                                    <h3 class="font-extrabold mb-2"><?php echo esc_html($title); ?></h3>
                                <?php endif; ?>
                                <?php if ($text) : ?>
                                    <p class="text-sm text-gray-600 leading-6"><?php echo esc_html($text); ?></p>
                                <?php endif; ?>
                            </div>
                        <?php endfor; ?>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    <?php endforeach; ?>

    <div class="mz-pdp-section border-b border-primary py-3">
        <button type="button" class="mz-section-toggle w-full flex items-center justify-between font-extrabold text-left">
            <span>Honest Answers for Common Questions</span>
            <span class="w-6 h-6 rounded-full border border-primary flex items-center justify-center text-sm">+</span>
        </button>

        <div class="mz-section-content hidden pt-4">
            <div class="bg-white rounded-2xl p-4 shadow-sm">
                <?php for ($i = 1; $i <= 8; $i++) :
                    $q = mz_field("pdp_faq_question_$i");
                    $a = mz_field("pdp_faq_answer_$i");
                    if (!$q && !$a) continue;
                ?>
                    <div class="mz-faq border-b last:border-b-0 border-[#ead6c8]">
                        <button type="button" class="mz-faq-toggle w-full py-4 flex justify-between text-left font-bold">
                            <?php echo esc_html($q); ?>
                            <span>+</span>
                        </button>
                        <div class="mz-faq-content hidden pb-4 text-sm text-gray-600 leading-6">
                            <?php echo esc_html($a); ?>
                        </div>
                    </div>
                <?php endfor; ?>
            </div>
        </div>
    </div>

   

</section>

<?php if ($banner_desktop) : ?>
<section class="w-full py-4">
    <a href="<?php echo esc_url($banner_url ?: '#'); ?>" class="block">
        <picture>
            <source media="(max-width: 767px)" srcset="<?php echo esc_url($banner_mobile); ?>">
            <img src="<?php echo esc_url($banner_desktop); ?>" class="w-full h-auto block">
        </picture>
    </a>
</section>
<?php endif; ?>



<!-- Related -->
<?php
$related_ids = wc_get_related_products($product_id, 4);
if (!empty($related_ids)) :
?>
<section class="bg-white py-8 md:py-12">
    <div class="max-w-[1400px] mx-auto px-3 md:px-4">
        <h2 class="text-center text-xl font-extrabold mb-6">Frequently bought together</h2>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <?php
            foreach ($related_ids as $rid) :
                $related_product = wc_get_product($rid);
                if (!$related_product) continue;

                $r_img = get_the_post_thumbnail_url($rid, 'large') ?: wc_placeholder_img_src();
            ?>
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-3">
                    <a href="<?php echo esc_url(get_permalink($rid)); ?>">
                        <img src="<?php echo esc_url($r_img); ?>" class="w-full aspect-square object-cover rounded-xl bg-[#eef5f1] mb-3">
                        <h3 class="font-bold text-sm line-clamp-1"><?php echo esc_html($related_product->get_name()); ?></h3>
                        <div class="font-extrabold text-sm mt-1"><?php echo wp_kses_post($related_product->get_price_html()); ?></div>
                    </a>

                    <button
                        type="button"
                        class="meziva-ajax-add-cart mt-3 w-full h-10 rounded-lg bg-primary text-white font-bold text-sm"
                        data-product-id="<?php echo esc_attr($rid); ?>"
                    >
                        Add To Cart
                    </button>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Mobile Sticky CTA -->
<div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-[9998] p-3 md:hidden">
    <div class="flex gap-3 items-center">
        <div class="flex items-center border-2 border-primary rounded-xl overflow-hidden bg-white">
            <button type="button" class="mz-pdp-qty-minus w-10 h-11 font-bold">−</button>
            <input type="number" id="mzPdpQtyMobile" value="1" min="1" class="w-10 text-center border-0 outline-none">
            <button type="button" class="mz-pdp-qty-plus w-10 h-11 font-bold">+</button>
        </div>

        <button
            type="button"
            class="mzAjaxAddCart flex-1 h-12 rounded-xl bg-primary text-white font-extrabold"
            data-product-id="<?php echo esc_attr($product_id); ?>"
        >
            Add To Cart
        </button>
    </div>
</div>

<script>
window.MZ_PDP_IMAGES = <?php echo wp_json_encode($gallery_images); ?>;
</script>

<?php endwhile; ?>

</main>

<?php get_footer(); ?>