<?php get_header(); ?>

<main class="main-page page-search">
    <div class="center">
        <h1><?php pll_e('Search') ?>: <?= !empty($_GET['s']) ? $_GET['s'] : '' ?></h1>

        <?php if (have_posts()) : ?>
            <?php while(have_posts()) : the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="block mb-[30px] overflow-hidden">
                    <h2 class="mt-[20px] !mt-[0px] hidden"><?php the_title(); ?></h2>

                    <div class="thumb float-left mr-[20px] mb-[10px]">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail(); ?>
                        <?php else : ?>
                            <img src="<?= bloginfo('template_url'); ?>/img/no-image.png" alt="">
                        <?php endif; ?>
                    </div>

                    <h2 class="mt-[0px]"><?php the_title(); ?></h2>
                    <div><?= wp_trim_words(get_the_excerpt(), 40); ?></div>
                </a>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
