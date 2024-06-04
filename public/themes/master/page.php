<?php get_header(); ?>

<main class="main-page">
    <div class="center">
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>

        <?php
            if ( comments_open() || get_comments_number() ) {
                comments_template();
            }
        ?>
    </div>
</main>

<?php get_footer(); ?>
