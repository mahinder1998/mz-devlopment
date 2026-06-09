<?php
get_header();
?>

<div class="max-w-[1400px] mx-auto px-4 py-10">

    <h1 class="text-4xl font-bold mb-8">
        <?php woocommerce_page_title(); ?>
    </h1>

    <?php if (woocommerce_product_loop()) : ?>

        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

            <?php
            while (have_posts()) :
                the_post();

                wc_get_template_part('content', 'product');

            endwhile;
            ?>

        </div>

        <?php woocommerce_pagination(); ?>

    <?php endif; ?>

</div>

<?php
get_footer();
?>