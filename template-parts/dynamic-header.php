<?php
/**
 * The template for displaying header.
 * ไฟล์นี้จะถูกเรียกใช้โดย header.php เมื่อ Elementor Theme Builder ไม่ได้เข้ามาควบคุม Header
 *
 * @package HelloElementor
 */
if ( ! defined( 'ABSPATH' ) ) {
    exit; // ป้องกันการเข้าถึงไฟล์โดยตรง
}

// ดึงชื่อเว็บไซต์และ Tagline
$site_name = get_bloginfo( 'name' );
$tagline   = get_bloginfo( 'description', 'display' );

// กำหนด Argument สำหรับเมนูนำทางหลัก (Desktop)
$desktop_menu_args = [
    'theme_location' => 'menu-1', // ตำแหน่งเมนูที่ตั้งใน WordPress
    'fallback_cb' => false, // ไม่แสดงเมนูสำรองถ้าไม่มีเมนูนี้
    'container' => false, // ไม่สร้าง div ครอบเมนู
    'echo' => false, // ไม่แสดงผลทันที แต่เก็บไว้ในตัวแปร
    'menu_class' => 'navbar-nav d-flex flex-row', // สำหรับ Desktop ใช้ flex-row
];

// กำหนด Argument สำหรับเมนูนำทางสำหรับ Mobile (ใน Offcanvas)
$mobile_menu_args = [
    'theme_location' => 'menu-1', // ตำแหน่งเมนูที่ตั้งใน WordPress
    'fallback_cb' => false, // ไม่แสดงเมนูสำรองถ้าไม่มีเมนูนี้
    'container' => false, // ไม่สร้าง div ครอบเมนู
    'echo' => false, // ไม่แสดงผลทันที แต่เก็บไว้ในตัวแvariables
    'menu_class' => 'navbar-nav d-flex flex-column', // สำหรับ Mobile ใช้ flex-column
];


// ดึงเมนูนำทางหลักสำหรับ Desktop
$header_nav_menu = wp_nav_menu( $desktop_menu_args ); // ใช้ $desktop_menu_args
// ดึงเมนูนำทางสำหรับ Mobile (ใช้เมนูเดียวกัน แต่เป็นการเรียกแยกเพื่อไม่ให้ ID ซ้ำซ้อน)
$header_mobile_nav_menu = wp_nav_menu( $mobile_menu_args ); // ใช้ $mobile_menu_args

// ดึง URL ของโลโก้มือถือจาก Customizer
$mobile_logo_id = get_theme_mod( 'hello_elementor_child_mobile_logo' );
$mobile_logo_url = '';
if ( $mobile_logo_id ) {
    $mobile_logo_url = wp_get_attachment_image_url( $mobile_logo_id, 'full' );
}

?>

<header id="site-header" class="gustabe-header-full-width">
    <div class="header-wrapper">
        <div class="header-top-bar">
            <div class="container-fluid py-2 px-4 d-flex justify-content-end align-items-center">
                </div>
        </div>
        <div class="header-main-nav">
            <div class="container-fluid d-flex justify-content-between align-items-center py-3 px-4">
                <div class="site-branding d-flex align-items-center">
                    <?php
                    // โลโก้สำหรับ Desktop (จะถูกซ่อนบนมือถือด้วย CSS)
                    if ( has_custom_logo() ) {
                        $custom_logo_id = get_theme_mod( 'custom_logo' );
                        $desktop_logo = wp_get_attachment_image( $custom_logo_id, 'full', false, array( 'class' => 'custom-logo desktop-logo' ) );
                        echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="custom-logo-link" rel="home" aria-current="page">' . $desktop_logo . '</a>';
                    } elseif ( $site_name ) {
                        // ถ้าไม่มีโลโก้หลัก ให้แสดงชื่อเว็บไซต์แทน (สำหรับ Desktop)
                        ?>
                        <div class="site-title desktop-logo"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'hello-elementor' ); ?>" rel="home">
                                <?php echo esc_html( $site_name ); ?>
                            </a>
                        </div>
                        <?php
                    }

                    // โลโก้สำหรับ Mobile (จะถูกซ่อนบน Desktop ด้วย CSS)
                    if ( ! empty( $mobile_logo_url ) ) {
                        ?>
                        <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="custom-logo-link-mobile mobile-logo" rel="home"> <img src="<?php echo esc_url( $mobile_logo_url ); ?>" class="custom-logo-mobile" alt="<?php bloginfo( 'name' ); ?>">
                        </a>
                        <?php
                    } elseif ( get_bloginfo( 'name' ) ) {
                        // ถ้าไม่มีโลโก้มือถือที่ตั้งค่าใน Customizer ให้แสดงชื่อเว็บไซต์แทน (สำหรับ Mobile)
                        ?>
                        <div class="site-title mobile-logo"> <a href="<?php echo esc_url( home_url( '/' ) ); ?>" title="<?php esc_attr_e( 'Home', 'hello-elementor' ); ?>" rel="home">
                                <?php echo esc_html( $site_name ); ?>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                </div>

                <?php if ( $header_nav_menu ) : ?>
                    <nav id="site-navigation" class="main-navigation d-none d-lg-block d-flex justify-content-end" aria-label="<?php echo esc_attr__( 'Main menu', 'hello-elementor' ); ?>">
                        <?php
                        echo $header_nav_menu;
                        ?>
                    </nav>
                <?php endif; ?>

                <div class="header-actions d-flex align-items-center">
                    <div class="search-toggle me-3">
                        <a href="#" class="search-icon-link" data-bs-toggle="modal" data-bs-target="#search-popup-modal">
                            <i class="fas fa-search"></i>
                        </a>
                    </div>

                    <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="cart-contents position-relative me-3">
                        <i class="fas fa-shopping-cart"></i> <span class="cart-count position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            <?php echo WC()->cart->get_cart_contents_count(); ?>
                        </span>
                    </a>

                    <?php if ( is_user_logged_in() ) :
                        $current_user = wp_get_current_user();
                    ?>
                        <div class="dropdown">
                            <a href="#" class="my-account-link-logged-in-split-text me-3 d-flex align-items-center" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="avatar-wrapper me-2">
                                    <?php echo get_avatar( $current_user->ID, 40 ); ?>
                                </span>
                                <span class="d-flex flex-column">
                                    <span class="split-text-greeting"><?php echo esc_html__( 'สวัสดี', 'hello-elementor-child' ); ?></span>
                                    <span class="split-text-username"><?php echo esc_html( $current_user->display_name ); ?></span>
                                </span>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="<?php echo esc_url( wc_get_page_permalink( 'myaccount' ) ); ?>"><?php echo esc_html__( 'บัญชีของฉัน', 'hello-elementor-child' ); ?></a></li>
                                <li><a class="dropdown-item" href="<?php echo esc_url( wc_get_account_endpoint_url( 'orders' ) ); ?>"><?php echo esc_html__( 'คำสั่งซื้อ', 'hello-elementor-child' ); ?></a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item" href="<?php echo esc_url( wp_logout_url( home_url() ) ); ?>"><?php echo esc_html__( 'ออกจากระบบ', 'hello-elementor-child' ); ?></a></li>
                            </ul>
                        </div>
                    <?php else : ?>
                        <a href="<?php echo esc_url( home_url( '/login/' ) ); ?>" class="my-account-link-split-text me-3 d-flex align-items-center">
                            <i class="fas fa-user me-2"></i>
                            <span class="d-flex flex-column">
                                <span class="split-text-login"><?php echo esc_html__( 'เข้าสู่ระบบ', 'hello-elementor-child' ); ?></span>
                                <span class="split-text-register"><?php echo esc_html__( 'สมัครสมาชิก', 'hello-elementor-child' ); ?></span>
                            </span>
                        </a>
                    <?php endif; ?>

                    <button class="navbar-toggler d-lg-none ms-3" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasMenu" aria-controls="offcanvasMenu" aria-expanded="false" aria-label="<?php echo esc_attr__( 'Menu', 'hello-elementor' ); ?>">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</header>

<div class="modal fade" id="search-popup-modal" tabindex="-1" aria-labelledby="searchPopupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="searchPopupModalLabel"><?php echo esc_html__( 'ค้นหา', 'hello-elementor-child' ); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
                    <div class="search-input-container mb-3"> <input type="search" class="form-control form-control-lg search-input-field" placeholder="<?php echo esc_attr__( 'ค้นหา...', 'hello-elementor-child' ); ?>" value="<?php echo get_search_query(); ?>" name="s">
                        <button type="submit" class="search-submit-button-internal"><i class="fas fa-search"></i></button>
                    </div>

                    <p class="search-filter-label mt-3"><?php echo esc_html__( 'เลือกประเภทการค้นหา:', 'hello-elementor-child' ); ?></p>
                    <div class="search-filter-buttons d-flex flex-wrap gap-2">
                        <input type="radio" class="btn-check" name="search_type" id="searchTypeAll" value="all" <?php selected( 'all', get_query_var( 'search_type' ) ); ?>>
                        <label class="btn btn-outline-primary" for="searchTypeAll"><?php echo esc_html__( 'ทุกอย่าง', 'hello-elementor-child' ); ?></label>

                        <input type="radio" class="btn-check" name="search_type" id="searchTypePost" value="post" <?php selected( 'post', get_query_var( 'search_type' ) ); ?>>
                        <label class="btn btn-outline-primary" for="searchTypePost"><?php echo esc_html__( 'บทความ', 'hello-elementor-child' ); ?></label>

                        <input type="radio" class="btn-check" name="search_type" id="searchTypeProduct" value="product" <?php selected( 'product', get_query_var( 'search_type' ) ); ?>>
                        <label class="btn btn-outline-primary" for="searchTypeProduct"><?php echo esc_html__( 'สินค้า', 'hello-elementor-child' ); ?></label>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>


<?php if ( $header_mobile_nav_menu ) : ?>
    <div class="offcanvas offcanvas-start d-lg-none" tabindex="-1" id="offcanvasMenu" aria-labelledby="offcanvasMenuLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasMenuLabel"><?php echo esc_html__( 'Menu', 'hello-elementor' ); ?></h5>
            <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php echo esc_attr__( 'Close', 'hello-elementor' ); ?>"></button>
        </div>
        <div class="offcanvas-body">
            <?php
            // แสดงเมนูสำหรับมือถือ
            echo $header_mobile_nav_menu; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
            ?>
        </div>
    </div>
<?php endif; ?>