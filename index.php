<?php
get_header();
?>

<main class="bg-white">

    <?php if (is_cart() || is_checkout() || is_account_page()) : ?>

        <div class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8 py-8">
            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>
        </div>

    <?php else : ?>

        <div class="max-w-[1400px] mx-auto px-4 md:px-6 lg:px-8">

            <?php
            while (have_posts()) :
                the_post();
                the_content();
            endwhile;
            ?>

        </div>

    <?php endif; ?>

</main>

<?php
get_footer();
?>