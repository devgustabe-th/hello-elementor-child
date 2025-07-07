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

/*
ตัวอย่างโค้ดสำหรับลบ meta boxes ใน Product (จาก functions.php เดิม)
add_action('add_meta_boxes', function () {
    remove_meta_box('postexcerpt', 'product', 'normal');
    remove_meta_box('slugdiv', 'product', 'normal');
    remove_meta_box('commentsdiv', 'product', 'normal');
    remove_meta_box('revisionsdiv', 'product', 'normal');
    remove_meta_box('postcustom', 'product', 'normal');
}, 99);
*/