<?php
/**
 * Template Name: Custom WooCommerce Login/Register
 * Description: A custom template for WooCommerce Login and Registration.
 */

get_header(); // เรียกใช้ส่วนหัวของเว็บไซต์ (Header)

// ตรวจสอบว่าผู้ใช้ล็อกอินอยู่หรือไม่
if ( is_user_logged_in() ) {
    // ถ้าล็อกอินอยู่ ให้แสดงเนื้อหาของหน้า My Account หรือข้อความยินดีต้อนรับ
    ?>
    <div id="primary" class="content-area">
        <main id="main" class="site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><div class="entry-content">
                    <?php
                    // แสดง Shortcode My Account ซึ่งจะแสดงแดชบอร์ดสำหรับผู้ใช้ที่ล็อกอินแล้ว
                    // สามารถปรับแต่งหรือลบส่วนนี้ได้หากต้องการให้หน้านี้เป็นแค่ Login/Register จริงๆ
                    echo do_shortcode( '[woocommerce_my_account]' );
                    ?>
                </div></article>
        </main></div><?php
} else {
    // ถ้ายังไม่ได้ล็อกอิน ให้แสดงฟอร์ม Login และ Register
    ?>
    <div id="primary" class="content-area custom-login-register-page">
        <main id="main" class="site-main">
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <header class="entry-header">
                    <?php the_title( '<h1 class="entry-title">', '</h1>' ); ?>
                </header><div class="entry-content custom-login-register-forms">
                    <?php
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
}

get_footer(); // เรียกใช้ส่วนท้ายของเว็บไซต์ (Footer)
?>