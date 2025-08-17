<?php
/**
 * The Template for displaying products in a product category. Simply includes the archive template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/taxonomy-product-cat.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     4.7.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header('shop');

$category = get_queried_object();
?>

<main id="shop">
    <div class="center">
        <shop :category="'<?= $category->term_id ?>'"></shop>
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
