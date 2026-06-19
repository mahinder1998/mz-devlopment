<?php
get_header();
?>

<main class="py-12">

    <?php while(have_posts()) : the_post(); ?>

        <article class="max-w-4xl mx-auto px-5">

            <div class="mb-5 text-sm text-gray-500">

                <?php echo get_the_date(); ?>

            </div>

            <h1 class="text-4xl md:text-6xl font-bold mb-8 leading-tight">

                <?php the_title(); ?>

            </h1>

            <?php if(has_post_thumbnail()) : ?>

                <div class="mb-10">

                    <?php the_post_thumbnail(
                        'full',
                        [
                            'class' =>
                            'w-full rounded-3xl'
                        ]
                    ); ?>

                </div>

            <?php endif; ?>

            <div class="prose prose-lg max-w-none">

                <?php the_content(); ?>

            </div>

        </article>

    <?php endwhile; ?>

</main>

<?php
get_footer();
?>