<?php

// ========== ENQUEUE THEME STYLES ==========
add_action('wp_enqueue_scripts', function () {
    if (!is_admin()) {
        wp_enqueue_style('parent-style', get_template_directory_uri() . '/style.css');
        wp_enqueue_style('child-style', get_stylesheet_directory_uri() . '/style.css', ['parent-style'], wp_get_theme()->get('Version'));
    }
});

// ========== ENQUEUE ADMIN STYLES ==========
// สามารถคงไว้ที่นี่ได้ เพราะ admin-style.css เป็นของ Child Theme โดยตรง
add_action('admin_enqueue_scripts', function () {
    wp_enqueue_style('gustabe-admin-style', get_stylesheet_directory_uri() . '/admin-style.css');
});

// ========== INCLUDE CUSTOM FUNCTIONALITY FILES ==========
// ตรวจสอบให้แน่ใจว่าโฟลเดอร์ 'includes' และไฟล์เหล่านี้มีอยู่จริงใน Child Theme ของคุณ
$includes_dir = get_stylesheet_directory() . '/includes/';

// Include Security functions
if (file_exists($includes_dir . 'security-functions.php')) {
    require_once $includes_dir . 'security-functions.php';
}

// Include Admin and Login Page Customization functions
if (file_exists($includes_dir . 'admin-login-customizations.php')) {
    require_once $includes_dir . 'admin-login-customizations.php';
}

// Include Media and SVG functions
if (file_exists($includes_dir . 'media-svg-functions.php')) {
    require_once $includes_dir . 'media-svg-functions.php';
}

// Include Comment Meta functions
if (file_exists($includes_dir . 'comment-meta-functions.php')) {
    require_once $includes_dir . 'comment-meta-functions.php';
}

// Include WooCommerce specific features
if (file_exists($includes_dir . 'woocommerce-features.php')) {
    require_once $includes_dir . 'woocommerce-features.php';
}

// ========== END ==========