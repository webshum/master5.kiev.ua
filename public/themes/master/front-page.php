<?php
/* Template Name: Home */

get_header();

$home = get_field('home');
$services = get_field('services');
$reviews = get_field('reviews');
$content = get_field('content');

?>

<main class="home-page">
    <div class="center">
        <h1><?php the_title(); ?></h1>

        <?php if (!empty($home)) : ?>
            <?= $home ?>
        <?php endif; ?>
    </div>

    <div class="mt-5">
        <?php
            if ( (comments_open() || get_comments_number()) && empty(get_field('onoff_comments')) ) {
                comments_template();
            }
        ?>
    </div>
</main>

<?php get_footer(); ?>
