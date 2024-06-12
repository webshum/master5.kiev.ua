<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Register theme defaults.
add_action('after_setup_theme', function () {
    show_admin_bar(false);

    add_theme_support('post-thumbnails');
    add_theme_support('title-tag');
    add_theme_support('comments');

    register_nav_menus([
        'navigation' => __('Navigation'),
    ]);
});

// Polylang
pll_register_string( 'search', 'Search', 'WordPress' );
pll_register_string( 'slogan', 'Slogan', 'WordPress' );
pll_register_string( 'comment', 'Comment', 'WordPress' );
pll_register_string( 'login', 'You must be logged in to post a comment.', 'WordPress' );
pll_register_string( 'logout', 'Logged in as. Log out?', 'WordPress' );
pll_register_string( 'comment-notes', 'Required fields are marked *', 'WordPress' );
pll_register_string( 'reply', 'Leave a Reply', 'WordPress' );
pll_register_string( 'reply-to', 'Leave a Reply to', 'WordPress' );
pll_register_string( 'reply-cancel', 'Cancel Reply', 'WordPress' );
pll_register_string( 'post-comment', 'Post Comment', 'WordPress' );
pll_register_string( 'name', 'Name', 'WordPress' );
pll_register_string( 'email', 'Email', 'WordPress' );
pll_register_string( 'website', 'Website', 'WordPress' );
pll_register_string( 'more', 'More', 'WordPress' );
pll_register_string( 'fresh-posts', 'Fresh posts', 'WordPress' );
pll_register_string( 'fresh-comments', 'Fresh comments', 'WordPress' );

// Open comments
add_filter( 'comments_open', function($open, $post_id) {
    $post = get_post($post_id);
    if ($post->post_type == 'post' || $post->post_type == 'page') {
        return true;
    }
    return $open;
}, 10, 2 );

// Register scripts and styles.
add_action('wp_enqueue_scripts', function () {
    $manifestPath = get_theme_file_path('assets/.vite/manifest.json');

    if (
        wp_get_environment_type() === 'local' &&
        is_array(wp_remote_get('http://localhost:5173/')) // is Vite.js running
    ) {
        wp_enqueue_script('vite', 'http://localhost:5173/@vite/client');
        wp_enqueue_script('wordplate', 'http://localhost:5173/resources/js/index.js');
    } elseif (file_exists($manifestPath)) {
        $manifest = json_decode(file_get_contents($manifestPath), true);
        wp_enqueue_script('wordplate', get_theme_file_uri('assets/' . $manifest['resources/js/index.js']['file']));
        wp_enqueue_style('wordplate', get_theme_file_uri('assets/' . $manifest['resources/js/index.js']['css'][0]));
    }
});

// Load scripts as modules.
add_filter('script_loader_tag', function (string $tag, string $handle, string $src) {
    if (in_array($handle, ['vite', 'wordplate'])) {
        return '<script type="module" src="' . esc_url($src) . '" defer></script>';
    }

    return $tag;
}, 10, 3);

// Remove admin menu items.
add_action('admin_init', function () {
    // remove_menu_page('edit-comments.php'); // Comments
    // remove_menu_page('edit.php?post_type=page'); // Pages
    // remove_menu_page('edit.php'); // Posts
    remove_menu_page('index.php'); // Dashboard
    // remove_menu_page('upload.php'); // Media
});

// Remove admin toolbar menu items.
add_action('admin_bar_menu', function (WP_Admin_Bar $menu) {
    $menu->remove_node('archive'); // Archive
    $menu->remove_node('comments'); // Comments
    $menu->remove_node('customize'); // Customize
    $menu->remove_node('dashboard'); // Dashboard
    $menu->remove_node('edit'); // Edit
    $menu->remove_node('menus'); // Menus
    $menu->remove_node('new-content'); // New Content
    $menu->remove_node('search'); // Search
    // $menu->remove_node('site-name'); // Site Name
    $menu->remove_node('themes'); // Themes
    $menu->remove_node('updates'); // Updates
    $menu->remove_node('view-site'); // Visit Site
    $menu->remove_node('view'); // View
    $menu->remove_node('widgets'); // Widgets
    $menu->remove_node('wp-logo'); // WordPress Logo
}, 999);

// Remove admin dashboard widgets.
add_action('wp_dashboard_setup', function () {
    remove_meta_box('dashboard_activity', 'dashboard', 'normal'); // Activity
    // remove_meta_box('dashboard_right_now', 'dashboard', 'normal'); // At a Glance
    remove_meta_box('dashboard_site_health', 'dashboard', 'normal'); // Site Health Status
    remove_meta_box('dashboard_primary', 'dashboard', 'side'); // WordPress Events and News
    remove_meta_box('dashboard_quick_press', 'dashboard', 'side'); // Quick Draft
});

// Add custom login form logo.
add_action('login_head', function () {
    $url = get_theme_file_uri('favicon.svg');

    $styles = [
        sprintf('background-image: url(%s)', $url),
        'width: 200px',
        'background-position: center',
        'background-size: contain',
    ];

    printf(
        '<style> .login h1 a { %s } </style>',
        implode(';', $styles)
    );
});

// Setup custom SMTP credentials.
add_action('phpmailer_init', function (PHPMailer $mailer) {
    $mailer->isSMTP();
    $mailer->SMTPAutoTLS = false;
    $mailer->SMTPAuth = env('MAIL_USERNAME') && env('MAIL_PASSWORD');
    $mailer->SMTPDebug = env('WP_DEBUG') ? SMTP::DEBUG_SERVER : SMTP::DEBUG_OFF;
    $mailer->SMTPSecure = env('MAIL_ENCRYPTION', 'tls');
    $mailer->Debugoutput = 'error_log';
    $mailer->Host = env('MAIL_HOST');
    $mailer->Port = env('MAIL_PORT', 587);
    $mailer->Username = env('MAIL_USERNAME');
    $mailer->Password = env('MAIL_PASSWORD');
    return $mailer;
});

add_filter('wp_mail_from', fn() => env('MAIL_FROM_ADDRESS', 'hello@example.com'));
add_filter('wp_mail_from_name', fn() => env('MAIL_FROM_NAME', 'Example'));

/*
|--------------------------------------------------------------------------
| Check to array empty
|--------------------------------------------------------------------------
*/
function isArrayEmpty($array) {
    if (empty($array)) {
        return true;
    }

    foreach ($array as $value) {
        if (is_array($value)) {
            if (!isArrayEmpty($value)) {
                return false;
            }
        } else {
            if (!empty($value)) {
                return false;
            }
        }
    }

    return true;
}

add_filter( 'pll_get_post_types', 'add_cpt_to_pll', 10, 2 );

function add_cpt_to_pll( $post_types, $is_settings ) {
    if ( $is_settings ) {
        // hides 'my_cpt' from the list of custom post types in Polylang settings
        unset( $post_types['acf-field-group'] );
    } else {
        // enables language and translation management for 'my_cpt'
        $post_types['acf-field-group'] = 'acf-field-group';
    }
    return $post_types;
}
