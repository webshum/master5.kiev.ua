<?php

if (!env('WP_DEBUG')) {
    @ini_set('display_errors', 0);
}

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
        'navigation_shop' => __('Navigation Shop'),
        'navigation_footer_shop' => __('Navigation Footer Shop'),
    ]);
});

// Polylang
if (function_exists('pll_register_string')) {
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
    pll_register_string( 'buy', 'Buy', 'WordPress' );
    pll_register_string( 'related_products', 'Related products', 'WordPress' );
}

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

add_action('init', function () {
    if (isset($_GET['testmail'])) {
        $sent = wp_mail('shumjachi@email.com', 'Test', 'Це тестовий лист');

        if (!$sent) {
            global $phpmailer;
            if (is_object($phpmailer)) {
                var_dump($phpmailer->ErrorInfo);
            }
        }

        var_dump($sent);
        die;
    }
});

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
    $productID = false;

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
    if (!empty($_POST['productID'])) $productID = $_POST['productID'];

    // $to = "shumjachi@gmail.com";
    $to = get_option('admin_email');
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
    $body .= "id: > $productID";
    $body .= '</body></html>';
    
    $headers = [
        'From' => 'info@master5.kiev.ua',
        'Reply-To' => 'info@master5.kiev.ua',
        'Content-Type: text/html; charset=UTF-8',
    ];
    
    $success = wp_mail($to, $subject, $body, $headers);
    // mail( $to, $subject, $body, $headers );
    
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

    $to = get_option('admin_email');
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

/*
|--------------------------------------------------------------------------
| REMOVE TABS
|--------------------------------------------------------------------------
*/
add_filter('woocommerce_product_tabs', 'remove_woocommerce_product_tabs', 98);
function remove_woocommerce_product_tabs($tabs) {
    unset($tabs['description']);       
    unset($tabs['additional_information']); 
    unset($tabs['reviews']);            
    return $tabs;
}

remove_action('woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10);

function is_template_name( string $template_name ): bool {
    if ( ! is_page() && ! is_singular() ) {
        return false;
    }

    $template_file = get_page_template_slug( get_queried_object_id() );

    if ( ! $template_file ) {
        return false;
    }

    $template_path = locate_template( $template_file );

    if ( ! $template_path ) {
        return false;
    }

    $template_data = get_file_data( $template_path, array(
        'Template Name' => 'Template Name',
    ));

    if ( empty($template_data['Template Name']) ) {
        return false;
    }

    return ( strtolower(trim($template_data['Template Name'])) === strtolower(trim($template_name)) );
}

function is_woocommerce_shop_area() {
    return is_shop() || is_product_category() || is_product_tag() || is_product();
}

/*
|--------------------------------------------------------------------------
| API NOVA POST
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () {
    register_rest_route('np/v1', '/regions', [
        'methods' => 'GET',
        'callback' => 'get_nova_poshta_regions',
        'permission_callback' => '__return_true',
    ]);

    register_rest_route('np/v1', '/cities', [
        'methods' => 'GET',
        'callback' => 'get_nova_poshta_cities',
        'permission_callback' => '__return_true',
        'args' => [
            'region_ref' => ['required' => true]
        ]
    ]);

    register_rest_route('np/v1', '/warehouses', [
        'methods' => 'GET',
        'callback' => 'get_nova_poshta_warehouses',
        'permission_callback' => '__return_true',
        'args' => [
            'city_ref' => ['required' => true]
        ]
    ]);
});

function get_nova_poshta_regions() {
    return nova_poshta_api_request('getAreas', new stdClass());
}

function get_nova_poshta_cities($request) {
    $region_ref = $request->get_param('region_ref');
    return nova_poshta_api_request('getCities', ['AreaRef' => $region_ref]);
}

function get_nova_poshta_warehouses($request) {
    $city_ref = $request->get_param('city_ref');
    return nova_poshta_api_request('getWarehouses', ['CityRef' => $city_ref]);
}

function nova_poshta_api_request($method, $params) {
    $api_key = env('API_KEY_NP');

    $payload = [
        'apiKey' => $api_key,
        'modelName' => 'Address',
        'calledMethod' => $method,
        'methodProperties' => $params
    ];

    $response = wp_remote_post('https://api.novaposhta.ua/v2.0/json/', [
        'body' => json_encode($payload),
        'headers' => ['Content-Type' => 'application/json'],
        'timeout' => 10,
    ]);

    $raw = wp_remote_retrieve_body($response);
    $data = json_decode($raw);
    return $data->data;
}

/*
|--------------------------------------------------------------------------
| API ORDER
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () {
    register_rest_route('myshop/v1', '/submit-order', [
        'methods'  => 'POST',
        'callback' => 'handle_vue_order',
        'permission_callback' => '__return_true'
    ]);
});

function handle_vue_order(WP_REST_Request $request) {
    $data = $request->get_json_params();
    $productID = absint($data['productId']);

    if (!empty($productID)) {
        $order = wc_create_order();
        $order->add_product(wc_get_product($productID), 1);

        $order->set_address([
            'first_name' => sanitize_text_field($data['name']),
            'phone'      => sanitize_text_field($data['phone']),
            'email'      => 'no-reply@example.com',
        ], 'billing');

        $order->update_meta_data('np_region', sanitize_text_field($data['region']));
        $order->update_meta_data('np_city', sanitize_text_field($data['city']));
        $order->update_meta_data('np_warehouse', sanitize_text_field($data['warehouse']));

        $order->calculate_totals();
        $order->update_status('processing');

        $order->save();

        WC()->mailer()->emails['WC_Email_New_Order']->trigger($order);

        return new WP_REST_Response([
            'status' => 'success',
            'order_id' => $order->get_id(),
            'order_key' => $order->get_order_key(),
            'redirect_url' => "/order-received/{$order->get_id()}/?key={$order->get_order_key()}&utm_nooverride=1",
            'data' => $data
        ]);
    }

    return new WP_REST_Response(['status' => 'error', 'message' => 'No product ID'], 400);
}


add_action('woocommerce_admin_order_data_after_billing_address', function($order){
    echo '<p><strong>Регіон:</strong> ' . esc_html($order->get_meta('np_region')) . '</p>';
    echo '<p><strong>Місто:</strong> ' . esc_html($order->get_meta('np_city')) . '</p>';
    echo '<p><strong>Відділення:</strong> ' . esc_html($order->get_meta('np_warehouse')) . '</p>';
});

add_action('woocommerce_email_after_order_table', function($order, $sent_to_admin, $plain_text, $email){
    $supported_ids = ['new_order', 'customer_processing_order', 'customer_completed_order'];

    if (in_array($email->id, $supported_ids)) {
        echo '<h2>Дані Нової Пошти</h2>';
        echo '<p><strong>Регіон:</strong> ' . esc_html($order->get_meta('np_region')) . '</p>';
        echo '<p><strong>Місто:</strong> ' . esc_html($order->get_meta('np_city')) . '</p>';
        echo '<p><strong>Відділення:</strong> ' . esc_html($order->get_meta('np_warehouse')) . '</p>';
    }
}, 20, 4);

/*
|--------------------------------------------------------------------------
| API ATTRIBUTES
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/attribute-terms', [
        'methods'  => 'GET',
        'callback' => 'get_attribute_terms_by_language',
        'permission_callback' => '__return_true'
    ]);
});

function get_attribute_terms_by_language($request) {
    $lang = $request->get_param('lang') ?: 'uk';

    if (!function_exists('pll_get_term_language')) {
        return new WP_Error('pll_missing', 'Polylang is not active', ['status' => 500]);
    }

    $attributes = wc_get_attribute_taxonomies();
    $results = [];

    foreach ($attributes as $attribute) {
        $taxonomy = wc_attribute_taxonomy_name($attribute->attribute_name);

        $terms = get_terms([
            'taxonomy' => $taxonomy,
            'hide_empty' => false,
        ]);

        $terms_data = [];

        foreach ($terms as $term) {
            $lang_key = 'lang_' . $lang;
            $translated_name = get_term_meta($term->term_id, $lang_key, true);

            if (!$translated_name) {
                continue;
            }

            $terms_data[] = [
                'id'   => $term->term_id,
                'slug' => $term->slug,
                'name' => $translated_name,
            ];
        }

        if (empty($terms_data)) {
            continue;
        }

        $label = wc_attribute_label($taxonomy);

        $results[] = [
            'id'    => $attribute->attribute_id,
            'slug'  => $attribute->attribute_name,
            'label' => $label,
            'terms' => $terms_data,
            'taxonomy' => $taxonomy
        ];
    }

    return rest_ensure_response($results);
}

/*
|--------------------------------------------------------------------------
| API FILTER PRODUCTS BY LANG
|--------------------------------------------------------------------------
*/
add_action('rest_api_init', function () {
    register_rest_route('custom/v1', '/products', [
        'methods'             => 'POST',
        'callback'            => 'custom_get_filtered_products',
        'permission_callback' => '__return_true',
    ]);
});

function custom_get_filtered_products($request) {
    $params = $request->get_params();
    $per_page = (int) ($params['per_page'] ?: 12);
    $page     = (int) ($params['page']?: 1);
    $offset   = ($page - 1) * $per_page;

    $args = [
        'post_type'      => 'product',
        'posts_per_page' => $per_page,
        'offset'         => $offset,
        'orderby'        => sanitize_text_field($request->get_param('orderby') ?: 'date'),
        'order'          => sanitize_text_field($request->get_param('order') ?: 'DESC'),
        'meta_query'     => [],
        'tax_query'      => [],
    ];

    if (!empty($params['lang'])) {
        $args['meta_query'][] = [
            'key'     => '_lang',
            'value'   => $params['lang'],
            'compare' => '=',
        ];
    }

    if (!empty($params['min_price'])) {
        $args['meta_query'][] = [
            'key'     => '_price',
            'value'   => floatval($params['min_price']),
            'compare' => '>=',
            'type'    => 'NUMERIC',
        ];
    }

    if (!empty($params['max_price'])) {
        $args['meta_query'][] = [
            'key'     => '_price',
            'value'   => floatval($params['max_price']),
            'compare' => '<=',
            'type'    => 'NUMERIC',
        ];
    }

    $attributes = $params['attributes'];

    if (is_array($attributes)) {
        foreach ($attributes as $attr) {
            $slugs = $attr['slug'] ?? [];

            if (
                empty($attr['attribute']) ||
                !taxonomy_exists($attr['attribute']) ||
                !is_array($slugs) ||
                count(array_filter($slugs)) === 0
            ) {
                continue;
            }

            $args['tax_query'][] = [
                'taxonomy' => sanitize_text_field($attr['attribute']),
                'field'    => 'slug',
                'terms'    => array_map('sanitize_text_field', $slugs),
                'operator' => !empty($attr['operator']) ? sanitize_text_field($attr['operator']) : 'IN',
            ];
        }
    }

    $query = new WP_Query($args);

    $products = [];
    foreach ($query->posts as $post) {
        $product = wc_get_product($post->ID);
        if (!$product) continue;

        $products[] = [
            'id'        => $product->get_id(),
            'name'      => $product->get_name(),
            'price'     => $product->get_price_html(),
            'currency'  => get_woocommerce_currency_symbol(),
            'lang'      => get_post_meta($product->get_id(), '_lang', true),
            'permalink' => get_permalink($product->get_id()),
            'image'     => wp_get_attachment_image_url($product->get_image_id(), 'medium'),
        ];
    }

    return rest_ensure_response([
        'products'    => $products,
        'total'       => $query->found_posts,
        'page'        => $page,
        'per_page'    => $per_page,
        'totalPages'  => ceil($query->found_posts / $per_page)
    ]);
}





