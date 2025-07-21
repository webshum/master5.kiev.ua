    <footer id="footer">
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
