<?php 
if (function_exists('is_woocommerce_shop_area') && is_woocommerce_shop_area()) {
    get_header('shop');
} else {
    get_header();
}

?>

<main class="main-page">
    <div class="center">
        <h1><?php the_title(); ?></h1>
        <?php the_content(); ?>

        <?php
            if (!is_page('order-received')) {
                if ( (comments_open() || get_comments_number()) && empty(get_field('onoff_comments')) ) {
                    comments_template();
                }
            }
        ?>
    </div>
</main>

<?php

if (function_exists('is_woocommerce_shop_area') && is_woocommerce_shop_area()) {
    get_footer('shop');
} else {
    get_footer();
}

?>
