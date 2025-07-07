<?php
/**
 * Template Name: Custom WooCommerce Login/Register
 * Description: A custom template for WooCommerce Login and Registration.
 */

// ===================================================================================
// เพิ่มโค้ดส่วนนี้: Redirect ไปยังหน้า My Account หากผู้ใช้ล็อกอินอยู่แล้ว
// ===================================================================================
if ( is_user_logged_in() && ! is_admin() ) { // is_admin() เพื่อป้องกันการ redirect loop ใน wp-admin
    wp_redirect( wc_get_page_permalink( 'myaccount' ) ); // Redirect ไปยังหน้า My Account หลักของ WooCommerce
    exit;
}
// ===================================================================================

get_header(); // เรียกใช้ส่วนหัวของเว็บไซต์ (Header)
?>

<div id="primary" class="content-area custom-login-register-page">
    <main id="main" class="site-main">
        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
            <header class="entry-header">
                <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
            </header><div class="entry-content custom-login-register-forms">
                <?php
                // ส่วนนี้จะแสดงผลก็ต่อเมื่อผู้ใช้ยังไม่ได้ล็อกอินเท่านั้น (เพราะโค้ด redirect ข้างบนจะทำงานเมื่อล็อกอินแล้ว)

                // แสดงฟอร์ม Login โดยใช้ Shortcode
                echo '<h2>' . esc_html__( 'เข้าสู่ระบบ', 'woocommerce' ) . '</h2>';
                echo do_shortcode( '[woocommerce_form_login]' );

                // แสดงฟอร์ม Register (จะแสดงต่อเมื่อเปิดใช้งานการลงทะเบียนบนหน้า My Account ใน WooCommerce Settings)
                if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
                    echo '<h2>' . esc_html__( 'ลงทะเบียน', 'woocommerce' ) . '</h2>';
                    echo do_shortcode( '[woocommerce_form_register]' );
                }
                ?>
            </div></article>
    </main></div><?php
get_footer(); // เรียกใช้ส่วนท้ายของเว็บไซต์ (Footer)
?>