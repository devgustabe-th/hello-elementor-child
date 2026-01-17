<?php
/**
 * Child theme functions
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Load child theme scripts and styles.
 * รวมการโหลดทั้งหมดไว้ในฟังก์ชันเดียวเพื่อความง่ายและมั่นใจว่าถูกเรียกใช้
 * จัดการลำดับการโหลดและ dependency อย่างเหมาะสม
 *
 * @return void
 */


function hello_elementor_child_enqueue_all_assets() {
    // 1. โหลด style.css ของธีมหลัก (Hello Elementor) ก่อนเสมอ
    wp_enqueue_style( 'hello-elementor-parent-style', get_template_directory_uri() . '/style.css' );

    // 2. โหลด style.css ของธีมลูก (ซึ่งมี CSS custom ของคุณ)
    wp_enqueue_style( 'hello-elementor-child-style', get_stylesheet_uri(), array( 'hello-elementor-parent-style' ), '1.0.0' );

    // 3. โหลด Bootstrap CSS
    wp_enqueue_style( 'bootstrap-css', get_stylesheet_directory_uri() . '/css/bootstrap.min.css', array( 'hello-elementor-child-style' ), '5.3.0' );

    // **4. ลบบรรทัด Font Awesome ตรงนี้ออกไป**
    // wp_enqueue_style( 'font-awesome', 'https://use.fontawesome.com/releases/v6.5.2/css/all.css', array(), '6.5.2' );


    // 5. โหลด Bootstrap JS bundle
    wp_enqueue_script( 'bootstrap-js', get_stylesheet_directory_uri() . '/js/bootstrap.bundle.min.js', array( 'jquery' ), '5.3.0', true );


    // 7. โหลด admin-login.js (ถ้ามี) เฉพาะในหน้า Admin Dashboard
    if ( is_admin() ) {
        wp_enqueue_script(
            'admin-login-script',
            get_stylesheet_directory_uri() . '/js/admin-login.js',
            array( 'jquery' ),
            '1.0.0',
            true
        );
    }
}
// เรียกใช้ฟังก์ชันนี้เพียงครั้งเดียวสำหรับส่วนหน้าบ้าน
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_all_assets' );



// **เพิ่มฟังก์ชันใหม่สำหรับโหลด Font Awesome ของคุณโดยเฉพาะ**
function hello_elementor_child_enqueue_font_awesome_cdn() {
    wp_enqueue_style( 'font-awesome-my-cdn', 'https://use.fontawesome.com/releases/v6.5.2/css/all.css', array(), '6.5.2' );
}
// เรียกใช้ด้วย priority ที่ต่ำกว่า (เลขน้อยกว่า) หรือเป็นค่า default เพื่อให้โหลดเร็ว
// แต่สูงกว่า Elementor จะดีกว่า (เช่น 10)
add_action( 'wp_enqueue_scripts', 'hello_elementor_child_enqueue_font_awesome_cdn', 10 );



// ฟังก์ชันสำหรับยกเลิกการโหลด Font Awesome ของ Elementor
function custom_dequeue_elementor_font_awesome() {
    // ใช้ ID handle ที่เจอในโค้ด HTML: 'font-awesome-css'
    wp_dequeue_style( 'font-awesome-css' ); // <-- เปลี่ยน/เพิ่มบรรทัดนี้ให้เป็น 'font-awesome-css'

    // ลอง Dequeue handle อื่นๆ เผื่อมี Elementor เวอร์ชันอื่น
    wp_dequeue_style( 'font-awesome-5' );
    wp_dequeue_style( 'font-awesome-4-shim' );
    wp_dequeue_style( 'elementor-icons-fa-solid' );
    wp_dequeue_style( 'elementor-icons-fa-regular' );
    wp_dequeue_style( 'elementor-icons-fa-brands' );
    wp_dequeue_style( 'elementor-icons' );
}
// Hook เข้าไปใน wp_enqueue_scripts ด้วย priority สูงๆ (เลขยิ่งมาก ยิ่งรันทีหลัง)
// เพื่อให้แน่ใจว่ามันรันหลังจาก Elementor enqueue style ของมันแล้ว
add_action( 'wp_enqueue_scripts', 'custom_dequeue_elementor_font_awesome', 999 );


// หากต้องการให้ asset เหล่านี้บางส่วนโหลดใน admin dashboard ด้วย (เช่น bootstrap, font-awesome)
// คุณอาจต้องใช้ add_action( 'admin_enqueue_scripts', 'hello_elementor_child_enqueue_all_assets' );
// แต่ต้องระวังไม่ให้โหลดโค้ดหน้าบ้านที่ไม่จำเป็นใน admin area

// รวมไฟล์ฟังก์ชันเสริมต่างๆ จากโฟลเดอร์ 'includes'
require_once get_stylesheet_directory() . '/includes/admin-login-customizations.php';
require_once get_stylesheet_directory() . '/includes/comment-meta-functions.php';
require_once get_stylesheet_directory() . '/includes/media-svg-functions.php';
require_once get_stylesheet_directory() . '/includes/security-functions.php';
require_once get_stylesheet_directory() . '/includes/woocommerce-features.php';



/**
 * เพิ่มการตั้งค่าโลโก้มือถือใน WordPress Customizer.
 * @param WP_Customize_Manager $wp_customize Customizer instance.
 * @return void
 */
function hello_elementor_child_customizer_mobile_logo( $wp_customize ) {

    // 1. เพิ่ม Section ใหม่ (หรือจะใช้ Site Identity ที่มีอยู่ก็ได้)
    $wp_customize->add_section( 'hello_elementor_child_mobile_logo_section', array(
        'title'      => esc_html__( 'โลโก้สำหรับมือถือ', 'hello-elementor-child' ),
        'priority'   => 30, // กำหนดลำดับการแสดงผล
        'description' => esc_html__( 'อัปโหลดโลโก้ที่จะแสดงบนอุปกรณ์มือถือและแท็บเล็ตแนวตั้ง', 'hello-elementor-child' ),
    ) );

    // 2. เพิ่ม Setting สำหรับเก็บค่า ID โลโก้มือถือ
    $wp_customize->add_setting( 'hello_elementor_child_mobile_logo', array(
        'default'   => '', // ค่าเริ่มต้นว่างเปล่า
        'type'      => 'theme_mod', // เก็บค่าเป็น theme_mod
        'capability' => 'edit_theme_options',
        'sanitize_callback' => 'absint', // <--- เปลี่ยนจาก 'esc_url_raw' เป็น 'absint' ตรงนี้
    ) );

    // 3. เพิ่ม Control (ตัวเลือกอัปโหลดรูปภาพ)
    $wp_customize->add_control( new WP_Customize_Media_Control( $wp_customize, 'hello_elementor_child_mobile_logo', array(
        'label'    => esc_html__( 'โลโก้สำหรับมือถือ/แท็บเล็ต', 'hello-elementor-child' ),
        'section'  => 'hello_elementor_child_mobile_logo_section', // ผูกกับ Section ที่สร้าง
        'mime_type' => 'image', // อนุญาตเฉพาะรูปภาพ
        'button_labels' => array(
            'select'       => esc_html__( 'เลือกโลโก้มือถือ', 'hello-elementor-child' ),
            'change'       => esc_html__( 'เปลี่ยนโลโก้มือถือ', 'hello-elementor-child' ),
            'remove'       => esc_html__( 'ลบโลโก้มือถือ', 'hello-elementor-child' ),
            'default'      => esc_html__( 'ค่าเริ่มต้น', 'hello-elementor-child' ),
            'placeholder'  => esc_html__( 'ไม่มีโลโก้', 'hello-elementor-child' ),
            'frame_title'  => esc_html__( 'เลือกโลโก้สำหรับมือถือ', 'hello-elementor-child' ),
            'frame_button' => esc_html__( 'เลือกรูปภาพ', 'hello-elementor-child' ),
        ),
    ) ) );
}
add_action( 'customize_register', 'hello_elementor_child_customizer_mobile_logo' );




// Add theme support for menus
function hello_elementor_child_register_menus() {
    register_nav_menus([
        'menu-1' => esc_html__( 'Primary Menu', 'hello-elementor-child' ),
        'footer-menu' => esc_html__( 'Footer Menu', 'hello-elementor-child' ),
    ]);
}
add_action( 'after_setup_theme', 'hello_elementor_child_register_menus' );


/**
 * Function to modify main WordPress search query.
 * Based on 'search_type' parameter, filter by post type.
 *
 * @param WP_Query $query The WP_Query instance (passed by reference).
 */
function hello_elementor_child_modify_search_query( $query ) {
    // ตรวจสอบว่าเป็นหน้าค้นหาหลักและไม่ใช่หน้า Admin และไม่ใช่การค้นหาผ่าน AJAX
    if ( $query->is_search() && ! is_admin() && $query->is_main_query() ) {

        $search_type = get_query_var( 'search_type' ); // ดึงค่า search_type จาก URL

        if ( $search_type === 'post' ) {
            $query->set( 'post_type', 'post' ); // ค้นหาเฉพาะบทความ
        } elseif ( $search_type === 'product' ) {
            $query->set( 'post_type', 'product' ); // ค้นหาเฉพาะสินค้า WooCommerce
        } elseif ( $search_type === 'all' || empty( $search_type ) ) {
            $query->set( 'post_type', array( 'post', 'product', 'page' ) ); // ค้นหาบทความ สินค้า และหน้าเพจ
        }
    }
}
add_action( 'pre_get_posts', 'hello_elementor_child_modify_search_query' );

/**
 * Register 'search_type' as a public query var.
 * This allows WordPress to recognize 'search_type' in URL parameters.
 *
 * @param array $vars The array of query variables.
 * @return array The filtered array of query variables.
 */
function hello_elementor_child_add_search_query_var( $vars ) {
    $vars[] = 'search_type';
    return $vars;
}
add_filter( 'query_vars', 'hello_elementor_child_add_search_query_var' );