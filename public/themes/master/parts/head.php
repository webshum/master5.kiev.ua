<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="google-site-verification" content="nNQxG4VXzWG-8ScZGzwCe1QJTPzexX8JQdoIr5kLtoc" />

    <link rel="icon" href="https://master5.kiev.ua/uploads/2024/08/cropped-favicon-32x32.png" sizes="32x32" />
    <link rel="icon" href="https://master5.kiev.ua/uploads/2024/08/cropped-favicon-192x192.png" sizes="192x192" />
    <link rel="apple-touch-icon" href="https://master5.kiev.ua/uploads/2024/08/cropped-favicon-180x180.png" />
    <meta name="msapplication-TileImage" content="https://master5.kiev.ua/uploads/2024/08/cropped-favicon-270x270.png" />

    <?php if (is_front_page() || is_home()) : ?>
        <script type="application/ld+json">
        {
            "@context": "https://schema.org",
            "@type": "BreadcrumbList",
            "itemListElement": [
                {
                    "@type": "ListItem",
                    "position": 1,
                    "name": "Головна",
                    "item": "https://master5.kiev.ua/"
                }
            ]
        }
        </script>
    <?php endif; ?>

    <?php wp_head(); ?>
</head>

<body <?php body_class(''); ?>>