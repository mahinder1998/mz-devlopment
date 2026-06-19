<?php
get_header();
?>

<main class="py-12">

    <?php while(have_posts()) : the_post(); ?>

        <article class="max-w-5xl mx-auto px-5">

            <header class="mb-10 text-center">

                <h1 class="text-4xl md:text-5xl font-bold mb-4">
                    <?php the_title(); ?>
                </h1>

            </header>

            <div class="prose prose-lg max-w-none">

                <?php the_content(); ?>

            </div>

        </article>

    <?php endwhile; ?>

</main>

<?php
get_footer();
?>