    <footer id="footer" class="footer-shop">
    	<div class="footer-main center">
    		<div class="footer-col">
    			<?php
                wp_nav_menu([
                    'theme_location' => 'navigation_footer_shop',
                    'container' => '',
                ]);
                ?>
    		</div>

    		<div class="footer-col">
    			<address>
    			    <?php if (!empty(get_fields('options')['s_phone'])) : ?>
    			        <a href="tel:<?= get_fields('options')['s_phone'] ?>" target="_blank">
    			            <svg><use xlink:href="#phone"></use></svg>
    			            <span><?= get_fields('options')['s_phone'] ?></span>
    			        </a>
    			    <?php endif; ?>

    			    <?php if (!empty(get_fields('options')['s_telegram'])) : ?>
    			        <a href="https://t.me/<?= get_fields('options')['s_telegram'] ?>" target="_blank">
    			            <svg><use xlink:href="#telegram"></use></svg>
    			        </a>
    			    <?php endif; ?>

    			    <?php if (!empty(get_fields('options')['s_viber'])) : ?>
    			        <a href="viber://chat?number=<?= get_fields('options')['s_viber'] ?>" target="_blank">
    			            <svg><use xlink:href="#viber"></use></svg>
    			        </a>
    			    <?php endif; ?>

    			    <?php if (!empty(get_fields('options')['s_instagram'])) : ?>
    			        <a href="<?= get_fields('options')['s_instagram'] ?>" target="_blank">
    			            <svg><use xlink:href="#instagram"></use></svg>
    			        </a>
    			    <?php endif; ?>

    			    <?php if (!empty(get_fields('options')['s_facebook'])) : ?>
    			        <a href="<?= get_fields('options')['s_facebook'] ?>" target="_blank">
    			            <svg><use xlink:href="#facebook"></use></svg>
    			        </a>
    			    <?php endif; ?>
    			</address>
    		</div>	
    	</div>

        <div class="center">
            <div class="copy">
                © <?= date('Y') ?> 
                <?php 
                    $lang = pll_current_language(); 

                    if (!empty(get_fields('options')["copyright_{$lang}"])) {
                        echo get_fields('options')["copyright_{$lang}"];
                    }  
                ?> 
            </div>
            
            <?php if (!empty(get_fields('options')['work_time'])) : ?>
                <div class="work-time"><?= get_fields('options')['work_time'] ?></div>
            <?php endif; ?>
        </div>
    </footer>
</div>

<?php get_template_part( 'parts/foot' ); ?>