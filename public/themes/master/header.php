<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open();  ?>

    <div class="wrapper">
        <header id="header">
            <div class="center flex justify-between items-end">
                <div>
                    <a href="/" class="logo">
                        <img src="<?php echo bloginfo('template_url'); ?>/img/logo.png" alt="">
                    </a>

                    <div class="slogan">
                        <h4>Отличный <span>"Домашний мастер"</span></h4>
                        <p>Заказать мастера на ремонт бойлера в Киеве у нас это просто!</p>
                    </div>
                </div>

                <div class="flex flex-col">
                    <form action="#" class="form-search">
                        <input type="text" name="s" placeholder="Пошук...">
                        <button type="submit">
                            <svg><use xlink:href="#search"></use></svg>
                        </button>
                    </form>

                    <div class="flex items-center">
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
                                <a href="tel:<?= get_fields('options')['phone'] ?>">
                                    <svg><use xlink:href="#phone"></use></svg>
                                    <span><?= get_fields('options')['phone'] ?></span>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty(get_fields('options')['telegram'])) : ?>
                                <a href="https://t.me/<?= get_fields('options')['telegram'] ?>">
                                    <svg><use xlink:href="#telegram"></use></svg>
                                </a>
                            <?php endif; ?>

                            <?php if (!empty(get_fields('options')['telegram'])) : ?>
                                <a href="viber://chat?number=<?= get_fields('options')['viber'] ?>">
                                    <svg><use xlink:href="#viber"></use></svg>
                                </a>
                            <?php endif; ?>
                        </address>
                    </div>
                </div>
            </div>
        </header>
