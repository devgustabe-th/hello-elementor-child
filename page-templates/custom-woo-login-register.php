<?php
/**
 * Template Name: Custom WooCommerce Login/Register
 * Description: A custom template for WooCommerce Login and Registration with responsive layout.
 */

// ===================================================================================
// เพิ่มโค้ดส่วนนี้: Redirect ไปยังหน้า My Account หากผู้ใช้ล็อกอินอยู่แล้ว
// ===================================================================================
if ( is_user_logged_in() && ! is_admin() ) {
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
            </header><div class="entry-content">
                <style>
                    /* สไตล์พื้นฐานสำหรับ Container ของฟอร์ม */
                    .forms-container {
                        max-width: 1200px;
                        margin: 40px auto;
                        padding: 20px;
                        box-sizing: border-box;
                    }

                    /* สไตล์สำหรับ Desktop (2 คอลัมน์) */
                    @media (min-width: 851px) { /* แสดง 2 คอลัมน์เมื่อหน้าจอกว้างกว่า 850px */
                        .forms-container {
                            display: flex;
                            flex-wrap: wrap;
                            justify-content: center;
                            gap: 30px;
                        }
                        .login-form-wrapper,
                        .register-form-wrapper {
                            flex: 1;
                            min-width: 380px;
                            background: #ffffff;
                            padding: 30px;
                            border-radius: 8px;
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                            border: 1px solid #eee;
                            box-sizing: border-box;
                        }
                        /* ซ่อนแท็บและเนื้อหาแท็บบน Desktop */
                        .tab-buttons,
                        .tab-content-mobile {
                            display: none;
                        }
                    }

                    /* สไตล์สำหรับ Mobile (Tabbed Design) */
                    @media (max-width: 850px) { /* แสดงแท็บเมื่อหน้าจอกว้างไม่เกิน 850px */
                        .forms-container {
                            flex-direction: column; /* เรียงเป็นคอลัมน์เดียว */
                            align-items: center;
                            padding: 15px; /* ลด padding รอบ container หลักบนมือถือ */
                            margin: 20px auto; /* ปรับ margin บนมือถือ */
                        }
                        /* ซ่อน wrapper ของฟอร์ม 2 คอลัมน์บนมือถือ */
                        .login-form-wrapper,
                        .register-form-wrapper {
                            display: none;
                        }

                        /* สไตล์สำหรับปุ่มแท็บ (Mobile) */
                        .tab-buttons {
                            display: flex;
                            width: 95%;
                            max-width: 400px;
                            margin-bottom: 20px;
                            border-bottom: 2px solid #eee;
                            justify-content: space-around;
                        }
                        .tab-button {
                            flex: 1;
                            padding: 15px 10px;
                            text-align: center;
                            cursor: pointer;
                            background-color: #f9f9f9;
                            border: 1px solid #eee;
                            border-bottom: none;
                            border-radius: 8px 8px 0 0;
                            font-size: 16px;
                            font-weight: bold;
                            color: #555;
                            transition: background-color 0.3s, color 0.3s;
                        }
                        .tab-button.active {
                            background-color: #ffffff;
                            color: rgb(2, 135, 135);
                            border-color: rgb(2, 135, 135);
                            border-bottom: 2px solid #ffffff; /* ซ่อนเส้นล่างของแท็บที่ active */
                            position: relative;
                            z-index: 1;
                        }
                        .tab-button:not(.active):hover {
                            background-color: #f0f0f0;
                        }

                        /* สไตล์สำหรับเนื้อหาแท็บ (Mobile) */
                        .tab-content-mobile {
                            display: block; /* แสดงเนื้อหาแท็บ */
                            width: 95%;
                            max-width: 400px;
                            background: #ffffff;
                            padding: 20px;
                            border-radius: 0 0 8px 8px; /* ปรับขอบโค้งให้เข้ากับแท็บ */
                            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                            border: 1px solid #eee;
                            border-top: none; /* ซ่อนเส้นบนของเนื้อหาแท็บ */
                            box-sizing: border-box;
                        }
                        .tab-pane {
                            display: none; /* ซ่อนทุก tab pane โดย default */
                        }
                        .tab-pane.active {
                            display: block; /* แสดงเฉพาะ tab pane ที่ active */
                        }
                    }

                    /* สไตล์ทั่วไปสำหรับฟอร์ม (ใช้ได้ทั้ง Desktop และ Mobile) */
                    .forms-container h2 {
                        text-align: center;
                        margin-bottom: 25px;
                        color: #333;
                        font-size: 24px;
                    }
                    .forms-container .woocommerce-form .button {
                        background-color: rgb(2, 135, 135);
                        color: #fff;
                        border: none;
                        padding: 10px 20px;
                        border-radius: 5px;
                        cursor: pointer;
                        font-size: 16px;
                        width: 100%;
                        box-sizing: border-box;
                    }
                    .forms-container .woocommerce-form .button:hover {
                        background-color: rgb(0, 100, 100);
                    }
                    .forms-container .woocommerce-form-row label {
                        display: block;
                        margin-bottom: 5px;
                        font-weight: bold;
                    }
                    .forms-container .woocommerce-form-row input[type="text"],
                    .forms-container .woocommerce-form-row input[type="email"],
                    .forms-container .woocommerce-form-row input[type="password"] {
                        width: 100%;
                        padding: 10px;
                        margin-bottom: 15px;
                        border: 1px solid #ddd;
                        border-radius: 5px;
                        box-sizing: border-box;
                    }
                    .forms-container .woocommerce-LostPassword,
                    .forms-container .woocommerce-form-login__rememberme {
                        text-align: center;
                        margin-top: 15px;
                    }
                    .forms-container .woocommerce-LostPassword {
                        margin-top: 10px;
                    }
                    .forms-container .csl-container {
                        margin-top: 20px;
                        text-align: center;
                    }
                    .forms-container .csl-logo-container {
                        display: flex;
                        flex-wrap: wrap;
                        justify-content: center;
                        gap: 10px;
                        margin-top: 15px;
                    }
                    .forms-container .csl-logo-link {
                        display: inline-block;
                        width: 40px;
                        height: 40px;
                        background-color: #eee;
                        border-radius: 50%;
                    }
                </style>

                <div class="forms-container">
                    <div class="login-form-wrapper">
                        <?php echo do_shortcode( '[custom_login_form]' ); ?>
                    </div><?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                        <div class="register-form-wrapper">
                            <?php echo do_shortcode( '[custom_register_form]' ); ?>
                        </div><?php endif; ?>

                    <div class="tab-buttons">
                        <button class="tab-button active" data-tab="login"><?php esc_html_e( 'Login', 'woocommerce' ); ?></button>
                        <?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                            <button class="tab-button" data-tab="register"><?php esc_html_e( 'Register', 'woocommerce' ); ?></button>
                        <?php endif; ?>
                    </div>

                    <div class="tab-content-mobile">
                        <div id="login" class="tab-pane active">
                            <?php echo do_shortcode( '[custom_login_form]' ); ?>
                        </div>
                        <?php if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) : ?>
                            <div id="register" class="tab-pane">
                                <?php echo do_shortcode( '[custom_register_form]' ); ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div></div></article></main></div><?php
// เพิ่ม JavaScript สำหรับการสลับแท็บ
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabPanes = document.querySelectorAll('.tab-pane');

    // ตรวจสอบว่ามีปุ่มแท็บและเนื้อหาแท็บอยู่บนหน้าจอหรือไม่ (จะแสดงเฉพาะบนมือถือ)
    if (tabButtons.length > 0 && tabPanes.length > 0) {
        tabButtons.forEach(button => {
            button.addEventListener('click', function() {
                const targetTab = this.dataset.tab;

                // ลบ active class ออกจากทุกปุ่มและทุกเนื้อหา
                tabButtons.forEach(btn => btn.classList.remove('active'));
                tabPanes.forEach(pane => pane.classList.remove('active'));

                // เพิ่ม active class ให้กับปุ่มที่ถูกคลิก
                this.classList.add('active');

                // แสดงเนื้อหาของแท็บที่ตรงกัน
                document.getElementById(targetTab).classList.add('active');
            });
        });
    }
});
</script>

<?php
get_footer(); // เรียกใช้ส่วนท้ายของเว็บไซต์ (Footer)
?>