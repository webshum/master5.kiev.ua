<?php

defined( 'ABSPATH' ) || exit;

get_header('shop');

?>

<main class="product-single">
	<div class="center">
		<?php woocommerce_content(); ?>
	</div>
</main>

<?php get_footer('shop'); ?>
