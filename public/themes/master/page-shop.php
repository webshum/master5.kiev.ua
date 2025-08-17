<?php
/* Template Name: Shop */

get_header('shop');
?>

<main id="shop">
    <div class="center">
        <shop></shop>
    </div>
</main>

<div class="center main-comment">
    <?php
        if (!is_page('order-received')) {
            if ( (comments_open() || get_comments_number()) && empty(get_field('onoff_comments')) ) {
                comments_template();
            }
        }
    ?>
</div>

<?php get_footer('shop'); ?>