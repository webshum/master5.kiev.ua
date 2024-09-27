<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php 
        wp_body_open(); 
        $lang = pll_current_language();
    ?>

    <div class="wrapper">
        <header id="header">
            <div class="center head-bar flex justify-between items-center">
                <div>
                    <a href="/" class="logo">
                        <img src="<?php echo bloginfo('template_url'); ?>/img/logo.png" alt="">
                    </a>

                    <div class="slogan">
                        <?php pll_e('Slogan') ?>
                    </div>
                </div>

                <?php if (!empty(get_fields('options')['work_time'])) : ?>
                    <div class="work-time"><?= get_fields('options')['work_time'] ?></div>
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
                            'theme_location' => 'navigation',
                            'container' => '',
                        ]);
                        ?>
                    </nav>

                    <address>
                        <?php if (!empty(get_fields('options')['phone'])) : ?>
                            <a href="tel:<?= get_fields('options')['phone'] ?>" target="_blank">
                                <svg><use xlink:href="#phone"></use></svg>
                                <span><?= get_fields('options')['phone'] ?></span>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty(get_fields('options')['telegram'])) : ?>
                            <a href="https://t.me/<?= get_fields('options')['telegram'] ?>" target="_blank">
                                <svg><use xlink:href="#telegram"></use></svg>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty(get_fields('options')['telegram'])) : ?>
                            <a href="viber://chat?number=<?= get_fields('options')['viber'] ?>" target="_blank">
                                <svg><use xlink:href="#viber"></use></svg>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty(get_fields('options')['instagram'])) : ?>
                            <a href="<?= get_fields('options')['instagram'] ?>" target="_blank">
                                <svg><use xlink:href="#instagram"></use></svg>
                            </a>
                        <?php endif; ?>

                        <?php if (!empty(get_fields('options')['facebook'])) : ?>
                            <a href="<?= get_fields('options')['facebook'] ?>" target="_blank">
                                <svg><use xlink:href="#facebook"></use></svg>
                            </a>
                        <?php endif; ?>
                    </address>
                </div>


                <?php 
                    $key = "discount_{$lang}";
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
