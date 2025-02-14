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
pll_register_string( 'tel', 'Tel', 'WordPress' );
pll_register_string( 'name', 'Name', 'WordPress' );
pll_register_string( 'submit', 'Submit', 'WordPress' );
pll_register_string( 'form_success', 'FormSuccess', 'WordPress' );
pll_register_string( 'captcha', 'Incorrect value', 'WordPress' );
pll_register_string( 'captcha', 'Enter a number', 'WordPress' );
pll_register_string( 'captcha', 'How many', 'WordPress' );

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
| ALLOW UPLOAD SVG
|--------------------------------------------------------------------------
*/
function allow_svg_upload($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
}
add_filter('upload_mimes', 'allow_svg_upload');

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

function custom_comment_reply_title($args) {
    $args['title_reply_before'] = '<div id="reply-title" class="custom-reply-title">';
    $args['title_reply_after'] = '</div>';
    return $args;
}
add_filter('comment_form_defaults', 'custom_comment_reply_title');

add_filter( 'show_admin_bar', 'custom_show_admin_bar' );

function custom_show_admin_bar( $show ) {
    if ( is_user_logged_in() ) {
        return true;
    } else {
        return false;
    }
}

/*
|--------------------------------------------------------------------------
| AJAX FORM
|--------------------------------------------------------------------------
*/
function javascript_variables(){ ?>
    <script type="text/javascript">
        const ajax_url = '<?php echo admin_url( "admin-ajax.php" ); ?>';
        const ajax_nonce = '<?php echo wp_create_nonce( "secure_nonce_name" ); ?>';
    </script><?php
}
add_action ( 'wp_head', 'javascript_variables' );

add_action('wp_ajax_send', 'send_form');
add_action('wp_ajax_nopriv_send', 'send_form');

function send_form() {
    $first_name = '';
    $last_name = '';
    $phone = '';
    $email = '';
    $date = '';
    $mounth = '';
    $year = '';
    $time = '';
    $model = '';
    $message = '';
    $slug = '';
    $link = '';
    $title = '';

    if (!empty($_POST['first_name'])) $first_name = $_POST['first_name'];
    if (!empty($_POST['last_name'])) $last_name = $_POST['last_name'];
    if (!empty($_POST['phone'])) $phone = $_POST['phone'];
    if (!empty($_POST['email'])) $email = $_POST['email'];
    if (!empty($_POST['date'])) $date = $_POST['date'];
    if (!empty($_POST['mounth'])) $mounth = $_POST['mounth'];
    if (!empty($_POST['year'])) $year = $_POST['year'];
    if (!empty($_POST['time'])) $time = $_POST['time'];
    if (!empty($_POST['model'])) $model = $_POST['model'];
    if (!empty($_POST['message'])) $message = $_POST['message'];
    if (!empty($_POST['slug'])) $slug = $_POST['slug'];
    if (!empty($_POST['link'])) $link = $_POST['link'];
    if (!empty($_POST['title'])) $title = $_POST['title'];

    // $to = get_option('admin_email');
    $to = "info@master5.kiev.ua";
    $subject = 'Повідомлення з master5.kiev.ua';

    $body = '<html>
        <head>
          <title>Повідомлення з master5.kiev.ua</title>
        </head>
        <body>';

    if (!empty($first_name)) $body .= "Ім'я: $first_name $last_name<br>";
    if (!empty($phone)) $body .= 'Телефон: ' . $phone . '<br>';
    if (!empty($email)) $body .= 'Email: ' . $email . '<br>';
    if (!empty($date)) $body .= 'Дата: ' . $date . '<br>';
    if (!empty($mounth)) $body .= 'Місяць: ' . $mounth . '<br>';
    if (!empty($year)) $body .= 'Рік: ' . $year . '<br>';
    if (!empty($time)) $body .= 'Час: ' . $time . '<br>';
    if (!empty($model)) $body .= 'Модель: ' . $model . '<br>';
    if (!empty($message)) $body .= 'Повідомлення: ' . $message . '<br>';
    if (!empty($slug)) $body .= 'Сторіка: ' . $slug . '<br>';
    if (!empty($link)) $body .= 'Посилання: ' . $link . '<br>';
    if (!empty($title)) $body .= 'Заголовок: ' . $title . '<br>';

    $body .= '</body></html>';
    
    $headers = [
        'From' => 'info@master5.kiev.ua',
        'Reply-To' => 'info@master5.kiev.ua',
        'Content-Type' => 'text/html; charset=UTF-8'
    ];
    
    // $result = mail($to, $subject, $body, implode("\r\n", $headers));
     
    mail( $to, $subject, $body, $headers );
    
    echo 'Done!';
    wp_die();
}

if ( function_exists('yoast_breadcrumb') ) {
    yoast_breadcrumb( '<p id="breadcrumbs">','</p>' );
}

/*
|--------------------------------------------------------------------------
| SEND COMMENT TO EMAIL
|--------------------------------------------------------------------------
*/
function send_comment_email($comment_id) {
    $comment = get_comment($comment_id);
    $post = get_post($comment->comment_post_ID);

    $to = 'info@master5.kiev.ua';
    $subject = 'Новий коментар на вашому сайті';
    $body = '<html>
        <head>
          <title>New comment master5.kiev.ua</title>
        </head>
        <body>';

    $body .= "Користувач: {$comment->comment_author}<br>";
    $body .= "Email: {$comment->comment_author_email}<br>";
    $body .= "Коментар: {$comment->comment_content}<br>";
    $body .= "Перейти до коментаря: " . get_permalink($post);

    $body .= '</body></html>';
    $headers = [
        'From' => 'info@master5.kiev.ua',
        'Reply-To' => 'info@master5.kiev.ua',
        'Content-Type' => 'text/html; charset=UTF-8'
    ];

    mail($to, $subject, $body, $headers);
}

add_action('comment_post', 'send_comment_email', 11, 2);

