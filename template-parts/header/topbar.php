<?php
$slides = [];
for ($i = 1; $i <= 5; $i++) {
    $line_1 = meziva_get_option("meziva_topbar_slide_{$i}_line_1", $i === 1 ? 'Product Of The Month : Milk Drops Brightening Serum' : '');
    $line_2 = meziva_get_option("meziva_topbar_slide_{$i}_line_2", $i === 1 ? 'Use code HURRY20 & Get FLAT 20% OFF' : '');
    if ($line_1 || $line_2) $slides[] = ['line_1' => $line_1, 'line_2' => $line_2];
}
if (!$slides) $slides[] = ['line_1' => 'Product Of The Month : Milk Drops Brightening Serum', 'line_2' => 'Use code HURRY20 & Get FLAT 20% OFF'];
?>
<div class="meziva-topbar bg-primary text-white text-center text-xs md:text-sm font-bold  relative z-50 overflow-hidden">
    <!-- <button type="button" id="mezivaTopbarPrev" class="absolute left-4 md:left-[32%] top-1/2 -translate-y-1/2 text-black text-xl z-10">‹</button> -->
    <div class="meziva-topbar-track relative min-h-[38px]">
        <?php foreach ($slides as $index => $slide) : ?>
            <div class="meziva-topbar-slide <?php echo $index === 0 ? 'is-active' : ''; ?> absolute inset-0 flex flex-col items-center justify-center leading-5 transition-all duration-500 <?php echo $index === 0 ? 'opacity-100 translate-y-0' : 'opacity-0 translate-y-4'; ?>">
                <?php if ($slide['line_1']) : ?><div><?php echo esc_html($slide['line_1']); ?></div><?php endif; ?>
                <?php if ($slide['line_2']) : ?><div><?php echo esc_html($slide['line_2']); ?></div><?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
    <!-- <button type="button" id="mezivaTopbarNext" class="absolute right-4 md:right-[32%] top-1/2 -translate-y-1/2 text-black text-xl z-10">›</button> -->
</div>
