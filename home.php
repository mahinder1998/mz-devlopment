<?php
get_header();
?>

<main class="py-16">

    <div class="max-w-7xl mx-auto px-5">

        <div class="text-center mb-12">

            <h1 class="text-5xl font-bold mb-4">
                Latest Articles
            </h1>

            <p class="text-gray-500">
                Tips, Guides & Updates
            </p>

        </div>

        <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">

            <?php while(have_posts()) : the_post(); ?>

                <article class="bg-white rounded-3xl overflow-hidden shadow hover:shadow-xl transition">

                    <a href="<?php the_permalink(); ?>">

                        <?php if(has_post_thumbnail()) : ?>

                            <?php the_post_thumbnail(
                                'large',
                                [
                                    'class' => 'w-full h-64 object-cover'
                                ]
                            ); ?>

                        <?php endif; ?>

                    </a>

                    <div class="p-6">

                        <div class="text-sm text-gray-500 mb-3">
                            <?php echo get_the_date(); ?>
                        </div>

                        <h2 class="text-2xl font-bold mb-3">

                            <a href="<?php the_permalink(); ?>">

                                <?php the_title(); ?>

                            </a>

                        </h2>

                        <div class="text-gray-600 mb-5">

                            <?php echo wp_trim_words(
                                get_the_excerpt(),
                                18
                            ); ?>

                        </div>

                        <a
                            href="<?php the_permalink(); ?>"
                            class="inline-flex items-center font-semibold text-primary"
                        >
                            Read More →
                        </a>

                    </div>

                </article>

            <?php endwhile; ?>

        </div>

        <div class="mt-12">

            <?php the_posts_pagination(); ?>

        </div>

    </div>

</main>

<?php
get_footer();
?>