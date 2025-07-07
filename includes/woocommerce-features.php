<?php
// ========== WOOCOMMERCE RELATED FEATURES ==========

// Redirect /my-account/ to /login/ if user is not logged in
add_action('template_redirect', 'gustabe_redirect_my_account_to_custom_login');
function gustabe_redirect_my_account_to_custom_login() {
    // ตรวจสอบว่าเป็นหน้า My Account และผู้ใช้ยังไม่ได้ล็อกอิน
    // และไม่ใช่ endpoint สำหรับการรีเซ็ตรหัสผ่านหรือลืมรหัสผ่าน
    if ( is_account_page() && ! is_user_logged_in() && ! is_wc_endpoint_url( 'lost-password' ) && ! is_wc_endpoint_url( 'reset-password' ) ) {
        // Redirect ไปยังหน้า Login Custom ของคุณ
        wp_safe_redirect( home_url( '/login/' ) ); // ตรวจสอบให้แน่ใจว่า slug ของหน้า Login Custom ของคุณคือ 'login'
        exit;
    }
}

// =========================================================================
// เพิ่ม Custom Shortcodes สำหรับ Login และ Register Forms
// =========================================================================

add_shortcode('custom_login_form', 'gustabe_custom_login_form_shortcode');
function gustabe_custom_login_form_shortcode() {
    // ตรวจสอบว่าผู้ใช้ล็อกอินอยู่แล้วหรือไม่ เพื่อป้องกันการแสดงฟอร์มซ้ำ
    if ( is_user_logged_in() ) {
        return ''; // ไม่ต้องแสดงฟอร์ม login ถ้าล็อกอินอยู่แล้ว
    }
    ob_start();
    wc_get_template('myaccount/form-login.php');
    return ob_get_clean();
}

add_shortcode('custom_register_form', 'gustabe_custom_register_form_shortcode');
function gustabe_custom_register_form_shortcode() {
    // ตรวจสอบว่าเปิดใช้งานการลงทะเบียน และผู้ใช้ยังไม่ได้ล็อกอิน
    if ( get_option('woocommerce_enable_myaccount_registration') === 'yes' && ! is_user_logged_in() ) {
        ob_start();
        wc_get_template('myaccount/form-registration.php');
        return ob_get_clean();
    } elseif ( ! is_user_logged_in() ) {
        return '<p>' . esc_html__( 'Registration is currently disabled.', 'woocommerce' ) . '</p>';
    }
    return ''; // ไม่ต้องแสดงฟอร์ม register ถ้าล็อกอินอยู่แล้ว
}

/*
// ตัวอย่างโค้ดสำหรับลบ meta boxes ใน Product (จาก functions.php เดิม)
add_action('add_meta_boxes', function () {
    remove_meta_box('postexcerpt', 'product', 'normal');
    remove_meta_box('slugdiv', 'product', 'normal');
    remove_meta_box('commentsdiv', 'product', 'normal');
    remove_meta_box('revisionsdiv', 'product', 'normal');
    remove_meta_box('postcustom', 'product', 'normal');
}, 99);
*/