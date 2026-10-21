<?php get_header(); ?>

<main class="main-page">
    <div class="center">
        <?php if (function_exists('custom_breadcrumbs')) custom_breadcrumbs(); ?>

        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>
    </div>

    <?php
        if ( (comments_open() || get_comments_number()) && empty(get_field('onoff_comments')) ) {
            comments_template();
        }
    ?>
</main>

<?php get_footer(); ?>
