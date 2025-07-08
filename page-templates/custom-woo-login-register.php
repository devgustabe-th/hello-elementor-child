<?php
/**
 * Template Name: Custom WooCommerce Login/Register
 * Description: A custom template for WooCommerce Login and Registration.
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
                    /* สไตล์สำหรับจัดวาง Form เป็น 2 คอลัมน์ */
                    .forms-container {
                        display: flex; /* ใช้ Flexbox เพื่อจัดวาง */
                        flex-wrap: wrap; /* ให้ขึ้นบรรทัดใหม่เมื่อหน้าจอเล็ก */
                        justify-content: center; /* จัดให้อยู่กึ่งกลาง */
                        gap: 30px; /* ระยะห่างระหว่างฟอร์ม */
                        max-width: 1200px; /* ความกว้างสูงสุดของ container */
                        margin: 0 auto; /* จัดให้อยู่กึ่งกลางหน้าจอ */
                        padding: 20px; /* Padding รอบ container หลัก */
                        box-sizing: border-box; /* สำคัญ: รวม padding และ border เข้าไปในการคำนวณความกว้าง */
                    }

                    .login-form-wrapper,
                    .register-form-wrapper {
                        flex: 1; /* ให้แต่ละคอลัมน์ขยายตามพื้นที่ */
                        min-width: 380px; /* ความกว้างขั้นต่ำ (ปรับได้) */
                        background: #ffffff; /* สีพื้นหลังของแต่ละกล่องฟอร์ม */
                        padding: 30px;
                        border-radius: 8px;
                        box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
                        border: 1px solid #eee;
                        box-sizing: border-box; /* สำคัญ: รวม padding และ border เข้าไปในการคำนวณความกว้าง */
                    }

                    /* สไตล์สำหรับหน้าจอขนาดเล็ก (Responsive) */
                    @media (max-width: 850px) { /* ปรับ breakpoint นี้ถ้าต้องการ */
                        .forms-container {
                            flex-direction: column; /* เรียงเป็นคอลัมน์เดียวบนมือถือ */
                            align-items: center; /* จัดให้อยู่กึ่งกลาง */
                            gap: 20px; /* ระยะห่างสำหรับมือถือ */
                        }
                        .login-form-wrapper,
                        .register-form-wrapper {
                            width: 90%; /* ความกว้างบนมือถือ */
                            min-width: unset; /* ยกเลิก min-width บนมือถือ */
                            max-width: 450px; /* จำกัดความกว้างบนมือถือ */
                        }
                    }

                    /* สไตล์สำหรับหัวข้อฟอร์ม */
                    .forms-container h2 {
                        text-align: center;
                        margin-bottom: 25px;
                        color: #333;
                        font-size: 24px;
                    }

                    /* สไตล์สำหรับปุ่ม submit */
                    .forms-container .woocommerce-form .button {
                        background-color: rgb(2, 135, 135); /* สีปุ่มตามที่คุณต้องการ */
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
                        background-color: rgb(0, 100, 100); /* สีเมื่อเมาส์ชี้ (แนะนำให้เข้มขึ้น) */
                    }

                    /* WooCommerce form fields */
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

                    /* สไตล์สำหรับลิงก์ลืมรหัสผ่าน / จำฉันไว้ */
                    .forms-container .woocommerce-LostPassword,
                    .forms-container .woocommerce-form-login__rememberme {
                        text-align: center;
                        margin-top: 15px;
                    }
                    .forms-container .woocommerce-LostPassword {
                        margin-top: 10px;
                    }
                    
                    /* Social login buttons (ถ้ามีปลั๊กอินอื่นแทรกเข้ามา) */
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
                        width: 40px; /* Example size */
                        height: 40px; /* Example size */
                        background-color: #eee; /* Placeholder */
                        border-radius: 50%;
                        /* Add specific background-image for each social logo */
                    }
                </style>

                <div class="forms-container">
                    <div class="login-form-wrapper">
                        <?php
                        // แสดงฟอร์ม Login โดยใช้ Shortcode ที่เรากำหนดเอง
                        
                        echo do_shortcode( '[custom_login_form]' );
                        ?>
                    </div><?php
                    // แสดงฟอร์ม Register (จะแสดงต่อเมื่อเปิดใช้งานการลงทะเบียนบนหน้า My Account ใน WooCommerce Settings)
                    if ( get_option( 'woocommerce_enable_myaccount_registration' ) === 'yes' ) {
                        ?>
                        <div class="register-form-wrapper">
                            <?php
                            
                            echo do_shortcode( '[custom_register_form]' );
                            ?>
                        </div><?php
                    }
                    ?>
                </div></div></article></main></div><?php
get_footer(); // เรียกใช้ส่วนท้ายของเว็บไซต์ (Footer)
?>