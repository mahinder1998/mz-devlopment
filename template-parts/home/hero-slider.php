<?php
$slides = [];

if (function_exists('get_field')) {
    for ($i = 1; $i <= 5; $i++) {
        $desktop_image = get_field("desktop_image_{$i}");
        $mobile_image  = get_field("mobile_image_{$i}");
        $slide_url     = get_field("slide_url_{$i}");

        if (!empty($desktop_image) || !empty($mobile_image)) {
            $slides[] = [
                'desktop_image' => $desktop_image,
                'mobile_image'  => $mobile_image,
                'slide_url'     => $slide_url,
            ];
        }
    }
}
?>

<?php if (!empty($slides)) : ?>
<section class="w-full bg-[#eaf7df]">
    <div class="relative w-full overflow-hidden">

        <div id="homeHeroSlider" class="flex transition-transform duration-500 ease-in-out">
            <?php foreach ($slides as $slide) :
                $desktop_image = $slide['desktop_image'] ?? null;
                $mobile_image  = $slide['mobile_image'] ?? null;
                $slide_url     = $slide['slide_url'] ?? '#';

                $desktop_url = is_array($desktop_image) ? ($desktop_image['url'] ?? '') : $desktop_image;
                $mobile_url  = is_array($mobile_image) ? ($mobile_image['url'] ?? '') : $mobile_image;

                if (!$desktop_url && $mobile_url) {
                    $desktop_url = $mobile_url;
                }

                if (!$mobile_url && $desktop_url) {
                    $mobile_url = $desktop_url;
                }

                if (!$desktop_url) {
                    continue;
                }

                $alt = is_array($desktop_image) ? ($desktop_image['alt'] ?? 'Hero Banner') : 'Hero Banner';
            ?>
                <a href="<?php echo esc_url($slide_url ?: '#'); ?>" class="min-w-full block">
                    <picture>
                        <source media="(max-width: 767px)" srcset="<?php echo esc_url($mobile_url); ?>">
                        <img 
                            src="<?php echo esc_url($desktop_url); ?>" 
                            alt="<?php echo esc_attr($alt); ?>"
                            class="w-full h-auto block object-cover"
                            loading="eager"
                        >
                    </picture>
                </a>
            <?php endforeach; ?>
        </div>

        <button 
            id="homeHeroPrev"
            class="hidden md:flex absolute left-5 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/80 shadow items-center justify-center text-2xl hover:bg-white transition"
            type="button"
        >
            ‹
        </button>

        <button 
            id="homeHeroNext"
            class="hidden md:flex absolute right-5 top-1/2 -translate-y-1/2 w-11 h-11 rounded-full bg-white/80 shadow items-center justify-center text-2xl hover:bg-white transition"
            type="button"
        >
            ›
        </button>

        <div id="homeHeroDots" class="absolute bottom-5 left-1/2 -translate-x-1/2 flex gap-2"></div>

    </div>
</section>
<?php endif; ?>