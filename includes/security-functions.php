<?php
// ========== CONFIGURATION: LOGIN SLUG ==========
// กำหนดชื่อ URL สำหรับเข้าสู่ระบบที่นี่ (แก้ที่เดียว มีผลทั้งไฟล์)
// ถ้าอยากเปลี่ยนทางเข้าจาก fasicare-login เป็นอย่างอื่น ให้แก้ตรงนี้ครับ
if ( ! defined( 'CUSTOM_LOGIN_SLUG' ) ) {
    define( 'CUSTOM_LOGIN_SLUG', 'fasicare-login' );
}

// ========== SECURITY: REDIRECT, HIDE LOGIN ==========

// เปลี่ยนเส้นทางจาก wp-login.php และ wp-admin ไปยัง 404 ถ้ายังไม่ได้ล็อกอิน
function gustabe_security_redirects() {
    // ตรวจสอบว่าเป็นการเข้าถึง wp-login.php
    if (strpos($_SERVER['REQUEST_URI'] ?? '', 'wp-login.php') !== false && ($_SERVER['REQUEST_METHOD'] ?? '') === 'GET') {
        if (!is_user_logged_in()) {
            wp_safe_redirect(home_url('/404'));
            exit;
        }
    }

    // ตรวจสอบว่าเป็นการเข้าถึง wp-admin แต่ไม่ใช่ AJAX request
    if (strpos($_SERVER['REQUEST_URI'] ?? '', 'wp-admin') !== false && !wp_doing_ajax() && (!isset($_GET['action']) || ($_GET['action'] ?? '') !== 'save_order_data')) {
        if (!is_user_logged_in()) {
            wp_safe_redirect(home_url('/404'));
            exit;
        }
    }

    // แก้ไขจุดที่ 1: ใช้ตัวแปร CUSTOM_LOGIN_SLUG แทนคำว่า fasicare-login
    if (strpos($_SERVER['REQUEST_URI'] ?? '', CUSTOM_LOGIN_SLUG) !== false) {
        
        global $user_login, $error, $interim_login;
        $user_login = ''; 
        $error = '';      
        $interim_login = (isset($_REQUEST['interim-login']) && $_REQUEST['interim_login'] === '1');
        
        require_once(ABSPATH . 'wp-login.php');
        exit;
    }
}
add_action('init', 'gustabe_security_redirects');

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
            return new WP_Error('ip_forbidden', __('ขออภัย ไม่อนุญาตให้ล็อกอินจาก IP นี้ (นอกประเทศไทย)', 'hello-elementor-child'));
        }
    }
    return $user;
}

// Redirect lost password to custom login path
add_filter('lostpassword_url', function () {
    // แก้ไขจุดที่ 2: ใช้ตัวแปร CUSTOM_LOGIN_SLUG สร้างลิงก์
    return home_url('/' . CUSTOM_LOGIN_SLUG . '?action=lostpassword');
});

// Redirect after logout to homepage
add_action('wp_logout', function () {
    wp_safe_redirect(home_url('/'));
    exit;
});

// ========== EMAIL LOGIN ATTEMPT ALERT ==========
add_action('wp_login_failed', 'gustabe_notify_admin_on_login_attempt');
function gustabe_notify_admin_on_login_attempt() {
    $admin_email = get_option('admin_email');
    $ip_address = $_SERVER['REMOTE_ADDR'] ?? ''; 
    $login_url = $_SERVER['REQUEST_URI'] ?? ''; 
    $message = "There was a login attempt from an unknown IP address: $ip_address on URL: $login_url";
    wp_mail($admin_email, 'Unknown Login Attempt', $message);
}