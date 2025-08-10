<?php 

get_template_part( 'parts/head' );
$lang = pll_current_language();

?>

    <div class="wrapper">
        <header id="header" class="header-shop">
            <div class="center head-bar flex justify-between items-center">
                <div>
                    <a href="/" class="logo">
                        <img src="<?php echo bloginfo('template_url'); ?>/img/logo.png" alt="">
                    </a>

                    <div class="slogan">
                        <?php pll_e('Slogan') ?>
                    </div>
                </div>

                <?php if (!empty(get_fields('options')['s_work_time'])) : ?>
                    <div class="work-time">
                        <?= get_fields('options')['s_work_time'] ?>
                        <div class="ic-cart">
                            <svg><use xlink:href="#cart"></use></svg>
                            <span>0</span>
                        </div>        
                    </div>
                <?php endif; ?>

                <form action="/" class="form-search">
                    <input type="text" name="s" placeholder="<?php pll_e('Search') ?>...">
                    <button type="submit">
                        <svg><use xlink:href="#search"></use></svg>
                    </button>
                </form>
            </div>

            <div class="head-main">
                <div class="center flex justify-between items-center">
                    <button class="btn-nav hidden">
                        <span></span>
                        <span></span>
                        <span></span>
                    </button>

                    <nav class="nav" role="navigation">
                        <?php
                        wp_nav_menu([
                            'theme_location' => 'navigation_shop',
                            'container' => '',
                        ]);
                        ?>
                    </nav>

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


                <?php 
                    $key = "s_discount_{$lang}";
                    if (!empty(get_fields('options')[$key]['onoff'])) : 
                ?>
                    <div class="discount">
                        <div class="center">
                            <p><?= get_fields('options')[$key]['title'] ?></p>

                            <?php if (get_fields('options')[$key]['link']['url']) : ?>
                                <?php $target = get_fields('options')[$key]['link']['target']; ?>
                                <a
                                    href="<?= get_fields('options')[$key]['link']['url'] ?>"
                                    <?= (!empty($target)) ? 'target="_blank"' : ''; ?>
                                >
                                    <?= get_fields('options')[$key]['link']['title'] ?> &#8250;
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </header>
