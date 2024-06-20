<?php get_header(); ?>

<main class="main-page page-search">
    <div class="center">
        <h1><?php pll_e('Search') ?>: <?= !empty($_GET['s']) ? $_GET['s'] : '' ?></h1>

        <?php if (have_posts()) : ?>
            <?php while(have_posts()) : the_post(); ?>
                <a href="<?php the_permalink(); ?>" class="flex gap-[30px] items-center">
                    <div class="thumb">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail(); ?>
                        <?php else : ?>
                            <img src="<?= bloginfo('template_url'); ?>/img/no-image.png" alt="">
                        <?php endif; ?>
                    </div>

                    <div>
                        <h2><?php the_title(); ?></h2>
                        <div><?php the_excerpt(); ?></div>
                    </div>
                </a>
            <?php endwhile; ?>
        <?php endif; ?>

    </div>
</main>

<?php get_footer(); ?>
