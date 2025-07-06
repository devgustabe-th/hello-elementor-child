<?php

// ========== ENQUEUE THEME & ADMIN STYLES ==========
add_action('wp_enqueue_scripts', function () {
    if (!is_admin()) {
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style'], wp_get_theme()->get('Version'));
    }
});

add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('gustabe-admin-style', get_stylesheet_directory_uri() . '/admin-style.css');
});

// ========== SECURITY: REDIRECT, HIDE LOGIN (fasicare-login) ==========

// Hide wp-login.php and wp-admin from non-logged-in users, custom login slug: fasicare-login





// เปลี่ยนเส้นทางจาก wp-login.php และ wp-admin ไปยัง 404 ถ้ายังไม่ได้ล็อกอิน
function custom_login_url_redirect() {
    // ตรวจสอบว่าเป็นการเข้าถึง wp-login.php
    if (strpos($_SERVER['REQUEST_URI'], 'wp-login.php') !== false && $_SERVER['REQUEST_METHOD'] === 'GET') {
        if (!is_user_logged_in()) {
            // ถ้าไม่ได้ล็อกอินให้ไปที่หน้า 404
            wp_safe_redirect(home_url('/404'));
            exit;
        }
    }

    // ตรวจสอบว่าเป็นการเข้าถึง wp-admin แต่ไม่ใช่ AJAX request
    if (strpos($_SERVER['REQUEST_URI'], 'wp-admin') !== false && !wp_doing_ajax() && (!isset($_GET['action']) || $_GET['action'] !== 'save_order_data')) {
        if (!is_user_logged_in()) {
            // ถ้าไม่ได้ล็อกอินและไม่ใช่ AJAX request ให้ไปที่หน้า 404
            wp_safe_redirect(home_url('/404'));
            exit;
        }
    }

    // ตรวจสอบว่า URL เป็น gustabelogin และโหลด wp-login.php
    if (strpos($_SERVER['REQUEST_URI'], 'fasicare-login') !== false) {
        require_once(ABSPATH . 'wp-login.php');
        exit;
    }
}
add_action('init', 'custom_login_url_redirect');

// บล็อกการเข้าถึง wp-admin ถ้าไม่ได้ล็อกอิน
function custom_redirect_wp_admin() {
    if (is_admin() && !is_user_logged_in() && !wp_doing_ajax()) {
        wp_safe_redirect(home_url('/404')); // เปลี่ยนเส้นทางไปหน้า 404
        exit;
    }
}
add_action('admin_init', 'custom_redirect_wp_admin');






// Restrict login by IP or Thai country (with transient cache)
add_filter('wp_authenticate_user', 'gustabe_login_restrict_ip_v2', 10, 2);
function gustabe_login_restrict_ip_v2($user, $password) {
    // ลิสต์ IP ที่อนุญาต
    $allowed_ips = ['171.97.76.4', '193.186.4.155', '171.96.231.17', '124.121.239.235', '124.120.246.16', '119.76.29.138'];
    $ip = $_SERVER['REMOTE_ADDR'] ?? '';
    if (!in_array($ip, $allowed_ips)) {
        $cache_key = 'gustabe_ip_' . md5($ip);
        $country = get_transient($cache_key);
        if (!$country) {
            $res = wp_remote_get("http://ip-api.com/json/$ip", ['timeout' => 2]);
            $data = json_decode(wp_remote_retrieve_body($res));
            $country = $data->countryCode ?? '';
            set_transient($cache_key, $country, 12 * HOUR_IN_SECONDS);
        }
        if ($country !== 'TH') {
            return new WP_Error('ip_forbidden', __('ขออภัย ไม่อนุญาตให้ล็อกอินจาก IP นี้ (นอกประเทศไทย)', 'your-text-domain'));
        }
    }
    return $user;
}

// Redirect lost password to custom login path
add_filter('lostpassword_url', function () {
    return home_url('/fasicare-login?action=lostpassword');
});

// Redirect after logout to homepage
add_action('wp_logout', function () {
    wp_safe_redirect(home_url('/'));
    exit;
});

// Change logout URL to homepage
add_filter('logout_url', function ($logout_url, $redirect) {
    return home_url('/');
}, 10, 2);

// ========== ADMIN & LOGIN PAGE CUSTOMIZATION ==========

// Change admin title
add_filter('admin_title', function ($admin_title) {
    return str_replace('&#8212; WordPress', '', $admin_title);
}, 10);

// Change login title
add_filter('login_title', function ($login_title) {
    return str_replace('&#8212; WordPress', '', $login_title);
}, 10);

// Custom login logo and background (edit URLs as needed)
add_action('login_enqueue_scripts', 'gustabe_custom_login_logo');
function gustabe_custom_login_logo() {
    $logo_url = '/wp-content/uploads/2024/10/Logo-web.svg';
    $background_url = '/wp-content/uploads/2024/10/b38d7c8.webp';
    $creditdev = 'GUSTABE';
    $url_dev = 'https://gustabe.com';
    ?>
    <style>
            body.login {
                background: linear-gradient(120deg, #b6fbff 0%, #83eaf1 100%);
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }
            body.login #login {
                background: #fff;
                border-radius: 20px;
                padding: 48px 40px 36px 40px;
                box-shadow: 0 10px 32px rgba(57, 205, 204, 0.14), 0 2px 8px rgba(44, 175, 174, 0.07);
                width: 410px;
                margin: 48px 4vw 0 0;
                text-align: center;
                border: none;
                position: relative;
                transition: box-shadow 0.2s;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            #loginform, .login form {
                background: none;
                box-shadow: none;
                padding: 0;
                border: none !important;
                width: 100%;
                max-width: 410px;
            }
            #login h1 a {
                background-image: url('<?php echo $logo_url; ?>');
                background-size: contain;
                width: 95px;
                height: 95px;
                border-radius: 50%;
                box-shadow: 0 1.5px 8px rgba(61,183,235,0.10);
                margin-bottom: 18px;
                margin-left: auto;
                margin-right: auto;
            }
            .login label {
                font-weight: 500;
                color: #189e9c;
                font-size: 16px;
            }
            .login input[type="text"], .login input[type="password"] {
                border-radius: 10px;
                border: 1.5px solid #a7f6e8;
                background: #edfffd;
                padding: 13px 14px;
                font-size: 17px;
                margin-top: 8px;
                margin-bottom: 17px;
                transition: border .22s, box-shadow .22s;
                box-shadow: 0 1px 8px rgba(110, 232, 222, 0.07);
                width: 100%;
                max-width: 100%;
            }
            .login input[type="text"]:focus, .login input[type="password"]:focus {
                border-color: #33e6d2;
                background: #e3faf7;
                outline: none;
                box-shadow: 0 2px 12px rgba(27,193,183,0.12);
            }
            .login input[type="submit"] {
                background: linear-gradient(90deg, #10d4b4 0%, #3fd0fc 100%);
                color: #fff;
                border-radius: 10px;
                border: none;
                font-weight: 600;
                font-size: 17px;
                padding: 13px 0;
                margin-top: 9px;
                width: 100%;
                box-shadow: 0 2px 10px rgba(61,183,235,0.10);
                transition: background .23s;
                cursor: pointer;
            }
            .login input[type="submit"]:hover {
                background: linear-gradient(90deg, #1bb6a4 0%, #10d4b4 100%);
            }
            .login .forgetmenot label {
                font-size: 14px;
                color: #66b1ae;
                font-weight: 400;
            }
            .custom-login-buttons {
                display: flex;
                justify-content: center;
                gap: 18px;
                margin: 26px 0 0 0;
                flex-wrap: wrap;
            }
            .custom-login-buttons a {
                background: linear-gradient(90deg, #e0fff7 0%, #b6fbff 100%);
                color: #1bb6a4 !important;
                border-radius: 7px;
                padding: 10px 24px;
                font-weight: 500;
                text-decoration: none;
                border: 1.2px solid #8bf5eb;
                box-shadow: 0 1.5px 8px rgba(42,202,191,0.07);
                transition: background .16s, border .16s;
                font-size: 16px;
                margin-bottom: 6px;
            }
            .custom-login-buttons a:hover {
                background: linear-gradient(90deg, #d2faf7 0%, #a7f6e8 100%);
                border: 1.7px solid #10d4b4;
                color: #189e9c !important;
            }
            .credits {
                margin-top: 20px !important;
                font-weight: 400;
                color: #a0b8b8;
                font-size: 14px;
                letter-spacing: 0.01em;
                text-align: center;
            }
            .credits a {
                color: #16d7bf;
                text-decoration: underline;
                font-weight: 600;
            }
            .credits a:hover {
                color: #189e9c;
            }
            @media (max-width: 1024px) {
                body.login {
                    justify-content: center;
                    /* ฟอร์มอยู่กลางเมื่อจอแคบ */
                }
                body.login #login {
                    width: 98vw;
                    min-width: 0;
                    margin: 30px 1vw 0 1vw;
                    padding: 28px 4vw 20px 4vw;
                }
                #loginform, .login form {
                    max-width: 99vw;
                }
            }
            @media (max-width: 640px) {
                body.login #login {
                    border-radius: 12px;
                    box-shadow: 0 4px 16px rgba(57, 205, 204, 0.13);
                    padding: 16px 2vw 16px 2vw;
                    width: 98vw;
                    margin: 0 auto;
                }
                #login h1 a {
                    width: 70px;
                    height: 70px;
                    margin-bottom: 10px;
                }
                .custom-login-buttons a {
                    width: 100%;
                    padding: 13px 0;
                    font-size: 15px;
                }
                .custom-login-buttons {
                    flex-direction: column;
                    gap: 10px;
                }
            }
    </style>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                ['.language-switcher', '#nav', '#backtoblog'].forEach(selector => {
                    const element = document.querySelector(selector);
                    if (element) element.remove();
                });
                // Custom login buttons
                const customButtonsDiv = document.createElement('div');
                customButtonsDiv.className = 'custom-login-buttons';
                customButtonsDiv.innerHTML = 
                    `<a href="<?php echo wp_lostpassword_url(); ?>">ลืมรหัสผ่าน?</a>
                    <a href="<?php echo esc_url(home_url()); ?>">กลับหน้าแรก</a>`;
                document.querySelector('#loginform')?.after(customButtonsDiv);

                // Credits
                const creditsDiv = document.createElement('div');
                creditsDiv.className = 'credits';
                creditsDiv.innerHTML = 'Developed and Designed by <a href="<?php echo esc_url($url_dev); ?>" target="_blank"><?php echo esc_html($creditdev); ?></a>';
                customButtonsDiv.after(creditsDiv);
            });
        </script>
    <?php
}

// Change login logo link & title
add_filter('login_headerurl', function() {
    return home_url();
});
add_filter('login_headertitle', function() {
    return 'กลับไปที่เว็บไซต์ fasicare';
});

// Add back button to lost password form
add_action('login_footer', function () {
    if (isset($_GET['action']) && $_GET['action'] === 'lostpassword') { ?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.search.includes("action=lostpassword")) {
                var formContainer = document.querySelector('form#lostpasswordform p.submit');
                if (formContainer) {
                    var backButton = document.createElement('a');
                    backButton.href = "<?php echo home_url('/fasicare-login'); ?>";
                    backButton.textContent = "Back";
                    backButton.className = "back-button";
                    var container = document.createElement('div');
                    container.className = "back-button-container";
                    container.appendChild(backButton);
                    container.appendChild(formContainer.querySelector('input[type="submit"]'));
                    formContainer.parentNode.replaceChild(container, formContainer);
                }
            }
        });
        </script>
        <style>
            .back-button-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; gap: 10px;}
            .back-button { padding: 10px 20px; background: #0073aa; color: #fff; border-radius: 5px; font-weight: bold; text-decoration: none;}
            .back-button:hover { background: #005a87; }
            .back-button-container input[type="submit"] { margin: 0; font-weight: bold; }
        </style>
    <?php }
});

// Replace WP logo in admin bar with text
add_action('admin_head', function() {
    ?>
    <style>#wp-admin-bar-wp-logo{display:none!important;}#wp-admin-bar-custom-text{display:inline-block;font-weight:bold;color:#fff!important;font-size:16px;line-height:32px;}</style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var logoItem = document.getElementById('wp-admin-bar-wp-logo');
        if (logoItem) {
            var newText = document.createElement('li');
            newText.id = 'wp-admin-bar-custom-text';
            newText.innerHTML = 'fasicare';
            logoItem.parentNode.replaceChild(newText, logoItem);
        }
    });
    </script>
    <?php
});

// Custom Welcome Panel
add_action('welcome_panel', 'gustabe_custom_welcome_panel');
function gustabe_custom_welcome_panel() {
    $current_user = wp_get_current_user();
    if ($current_user->exists()) {
        echo '<style>
        .welcome-panel-header {display: none;}
        .welcome-panel-content{min-height:auto;}
        .custom-welcome-panel-header_gustabe {font-size: 24px; font-weight: bold; color: #fff; padding: 20px; border-radius: 5px; text-align: center; margin: 20px 0;}
        .custom-welcome-panel-header_gustabe a {color: #fff; text-decoration: underline;}
        .custom-welcome-panel-header_gustabe a:hover {color: #ffcc00;}
        </style>';
        echo '<div class="custom-welcome-panel-header_gustabe">ยินดีต้อนรับ ' . esc_html($current_user->display_name) . ' สู่เว็บไซต์ <a href="'.esc_url(home_url()).'">fasicare</a></div>';
    }
}

// ========== EMAIL LOGIN ATTEMPT ALERT ==========
add_action('wp_login_failed', 'gustabe_notify_admin_on_login_attempt');
function gustabe_notify_admin_on_login_attempt() {
    $admin_email = get_option('admin_email');
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? '';
    $login_url = $_SERVER['REQUEST_URI'] ?? '';
    $message = "There was a login attempt from an unknown IP address: $ip_address on URL: $login_url";
    wp_mail($admin_email, 'Unknown Login Attempt', $message);
}

// ========== SVG UPLOAD & SANITIZE ==========
add_filter('upload_mimes', function ($file_types) {
    if (current_user_can('administrator')) {
        $file_types['svg'] = 'image/svg+xml';
    }
    return $file_types;
});
add_filter('wp_handle_upload_prefilter', 'gustabe_sanitize_svg');
function gustabe_sanitize_svg($file) {
    if (($file['type'] ?? '') === 'image/svg+xml') {
        $svg_content = file_get_contents($file['tmp_name']);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        if ($dom->loadXML($svg_content, LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_DTDATTR)) {
            $scripts = $dom->getElementsByTagName('script');
            while ($scripts->length > 0) {
                $scripts->item(0)->parentNode->removeChild($scripts->item(0));
            }
            $cleaned_svg = $dom->saveXML();
            file_put_contents($file['tmp_name'], $cleaned_svg);
        } else {
            wp_die(__('SVG file contains potential security risks.'));
        }
        libxml_clear_errors();
    }
    return $file;
}

// Inline SVG logo
add_filter('get_custom_logo', 'gustabe_inline_svg_custom_logo');
function gustabe_inline_svg_custom_logo($html) {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = get_attached_file($custom_logo_id);
    if ($custom_logo_id && pathinfo($logo_url, PATHINFO_EXTENSION) === 'svg') {
        $svg_content = file_get_contents($logo_url);
        if ($svg_content) {
            $width = '25';
            $height = '25';
            $style = 'border-radius:0.2em;overflow:hidden;';
            $svg_content = preg_replace('/<svg([^>]*?)>/', '<svg width="' . esc_attr($width) . '" height="' . esc_attr($height) . '" style="' . esc_attr($style) . '" $1>', $svg_content);
            $html = $svg_content;
        }
    }
    return $html;
}

// ========== COMMENT META: PHONE & LINE ==========
add_action('comment_post', 'gustabe_save_comment_meta_data');
function gustabe_save_comment_meta_data($comment_id) {
    if (isset($_POST['phone'])) {
        $phone = sanitize_text_field($_POST['phone']);
        add_comment_meta($comment_id, 'phone', $phone);
    }
    if (isset($_POST['lineuser'])) {
        $lineuser = sanitize_text_field($_POST['lineuser']);
        add_comment_meta($comment_id, 'lineuser', $lineuser);
    }
}

add_filter('manage_edit-comments_columns', function ($columns) {
    $columns['phone'] = 'Phone';
    $columns['lineuser'] = 'Line User';
    return $columns;
});
add_action('manage_comments_custom_column', function ($column, $comment_id) {
    if ($column === 'phone') {
        echo esc_html(get_comment_meta($comment_id, 'phone', true));
    }
    if ($column === 'lineuser') {
        echo esc_html(get_comment_meta($comment_id, 'lineuser', true));
    }
}, 10, 2);

add_filter('comment_text', function ($comment_text) {
    if (current_user_can('administrator')) {
        $phone = get_comment_meta(get_comment_ID(), 'phone', true);
        $lineuser = get_comment_meta(get_comment_ID(), 'lineuser', true);
        if (!empty($phone)) $comment_text .= '<p><strong>Phone:</strong> ' . esc_html($phone) . '</p>';
        if (!empty($lineuser)) $comment_text .= '<p><strong>Line User:</strong> ' . esc_html($lineuser) . '</p>';
    }
    return $comment_text;
});

// ========== HIDE WP LOGO IN ADMIN BAR ==========
add_action('admin_bar_menu', function ($wp_admin_bar) {
    $wp_admin_bar->remove_node('wp-logo');
}, 999);

// ========== HIDE SLUG/EXCERPT/COMMENTS/REVISIONS IN PRODUCT (leave out if WooCommerce removed) ==========
/*
add_action('add_meta_boxes', function () {
    remove_meta_box('postexcerpt', 'product', 'normal');
    remove_meta_box('slugdiv', 'product', 'normal');
    remove_meta_box('commentsdiv', 'product', 'normal');
    remove_meta_box('revisionsdiv', 'product', 'normal');
    remove_meta_box('postcustom', 'product', 'normal');
}, 99);
*/

// ========== END ==========







add_action('woocommerce_checkout_process', function () {
    if (!empty($_FILES)) {
        error_log("=== Fascirent $_FILES CHECK ===");
        error_log(print_r($_FILES, true));
    }
});



add_action('woocommerce_checkout_process', function () {
    die('<pre>' . print_r($_FILES, true) . '</pre>');
});