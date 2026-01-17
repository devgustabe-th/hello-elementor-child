<?php
// ========== ADMIN & LOGIN PAGE CUSTOMIZATION ==========
// Change admin title
add_filter('admin_title', function ($admin_title) {
    return str_replace('&#8212; WordPress', '', $admin_title);
}, 10);

// Change login title
add_filter('login_title', function ($login_title) {
    return str_replace('&#8212; WordPress', '', $login_title);
}, 10);

// Custom login logo and background (edit URLs as needed)
add_action('login_enqueue_scripts', 'gustabe_custom_login_logo');
function gustabe_custom_login_logo() {
    $logo_url = '/wp-content/uploads/2025/07/LOGOWEBHEAD.webp';
    $background_url = '/wp-content/uploads/2025/07/fasicare-Bg.webp';
    $creditdev = 'GUSTABE';
    $url_dev = 'https://gustabe.com';

    $background_style = '';
    if (!empty($background_url)) {
        $background_style = 'background-image: url(\'' . esc_url($background_url) . '\'); background-size: cover; background-position: center center; background-repeat: no-repeat;';
    } else {
        $background_style = 'background: linear-gradient(120deg, #b6fbff 0%, #83eaf1 100%);';
    }


    ?>
    <style>
            body.login {
                <?php echo $background_style; ?>
                min-height: 100vh;
                display: flex;
                align-items: center;
                justify-content: flex-end;
            }
            body.login #login {
                background: #fff;
                border-radius: 20px;
                padding: 48px 40px 36px 40px;
                box-shadow: 0 10px 32px rgba(57, 205, 204, 0.14), 0 2px 8px rgba(44, 175, 174, 0.07);
                width: 410px;
                margin: 48px 4vw 0 0;
                text-align: center;
                border: none;
                position: relative;
                transition: box-shadow 0.2s;
                display: flex;
                flex-direction: column;
                align-items: center;
            }
            #loginform, .login form {
                background: none;
                box-shadow: none;
                padding: 0;
                border: none !important;
                width: 100%;
                max-width: 410px;
            }
            #login h1 a {
                background-image: url('<?php echo $logo_url; ?>');
                background-size: contain;
                background-position: center center;
                width: 95px;
                height: 95px;
                margin-bottom: 18px;
                margin-left: auto;
                margin-right: auto;
            }
            .login label {
                font-weight: 500;
                color: #189e9c;
                font-size: 16px;
            }
            .login input[type="text"], .login input[type="password"] {
                border-radius: 10px;
                border: 1.5px solid #a7f6e8;
                background: #edfffd;
                padding: 13px 14px;
                font-size: 17px;
                margin-top: 8px;
                margin-bottom: 17px;
                transition: border .22s, box-shadow .22s;
                box-shadow: 0 1px 8px rgba(110, 232, 222, 0.07);
                width: 100%;
                max-width: 100%;
            }
            .login input[type="text"]:focus, .login input[type="password"]:focus {
                border-color: #33e6d2;
                background: #e3faf7;
                outline: none;
                box-shadow: 0 2px 12px rgba(27,193,183,0.12);
            }
            .login input[type="submit"] {
                background: linear-gradient(90deg, #10d4b4 0%, #3fd0fc 100%);
                color: #fff;
                border-radius: 10px;
                border: none;
                font-weight: 600;
                font-size: 17px;
                padding: 13px 0;
                margin-top: 9px;
                width: 100%;
                box-shadow: 0 2px 10px rgba(61,183,235,0.10);
                transition: background .23s;
                cursor: pointer;
            }
            .login input[type="submit"]:hover {
                background: linear-gradient(90deg, #1bb6a4 0%, #10d4b4 100%);
            }
            .login .forgetmenot label {
                font-size: 14px;
                color: #66b1ae;
                font-weight: 400;
            }
            .custom-login-buttons {
                display: flex;
                justify-content: center;
                gap: 18px;
                margin: 26px 0 0 0;
                flex-wrap: wrap;
            }
            .custom-login-buttons a {
                background: linear-gradient(90deg, #e0fff7 0%, #b6fbff 100%);
                color: #1bb6a4 !important;
                border-radius: 7px;
                padding: 10px 24px;
                font-weight: 500;
                text-decoration: none;
                border: 1.2px solid #8bf5eb;
                box-shadow: 0 1.5px 8px rgba(42,202,191,0.07);
                transition: background .16s, border .16s;
                font-size: 16px;
                margin-bottom: 6px;
            }
            .custom-login-buttons a:hover {
                background: linear-gradient(90deg, #d2faf7 0%, #a7f6e8 100%);
                border: 1.7px solid #10d4b4;
                color: #189e9c !important;
            }
            .credits {
                margin-top: 20px !important;
                font-weight: 400;
                color: #a0b8b8;
                font-size: 14px;
                letter-spacing: 0.01em;
                text-align: center;
            }
            .credits a {
                color: #16d7bf;
                text-decoration: underline;
                font-weight: 600;
            }
            .credits a:hover {
                color: #189e9c;
            }
            @media (max-width: 1024px) {
                body.login {
                    justify-content: center;
                    /* ฟอร์มอยู่กลางเมื่อจอแคบ */
                }
                body.login #login {
                    width: 98vw;
                    min-width: 0;
                    margin: 30px 1vw 0 1vw;
                    padding: 28px 4vw 20px 4vw;
                }
                #loginform, .login form {
                    max-width: 99vw;
                }
            }
            @media (max-width: 640px) {
                body.login #login {
                    border-radius: 12px;
                    box-shadow: 0 4px 16px rgba(57, 205, 204, 0.13);
                    padding: 16px 2vw 16px 2vw;
                    width: 98vw;
                    margin: 0 auto;
                }
                #login h1 a {
                    width: 70px;
                    height: 70px;
                    margin-bottom: 10px;
                }
                .custom-login-buttons a {
                    width: 100%;
                    padding: 13px 0;
                    font-size: 15px;
                }
                .custom-login-buttons {
                    flex-direction: column;
                    gap: 10px;
                }
            }
    </style>


        <script>
            document.addEventListener('DOMContentLoaded', function() {
                ['.language-switcher', '#nav', '#backtoblog'].forEach(selector => {
                    const element = document.querySelector(selector);
                    if (element) element.remove();
                });
                // Custom login buttons
                const customButtonsDiv = document.createElement('div');
                customButtonsDiv.className = 'custom-login-buttons';
                customButtonsDiv.innerHTML = 
                    `<a href="<?php echo wp_lostpassword_url(); ?>">ลืมรหัสผ่าน?</a>
                    <a href="<?php echo esc_url(home_url()); ?>">กลับหน้าแรก</a>`;
                document.querySelector('#loginform')?.after(customButtonsDiv);

                // Credits
                const creditsDiv = document.createElement('div');
                creditsDiv.className = 'credits';
                creditsDiv.innerHTML = 'Developed and Designed by <a href="<?php echo esc_url($url_dev); ?>" target="_blank"><?php echo esc_html($creditdev); ?></a>';
                customButtonsDiv.after(creditsDiv);
            });
        </script>
    <?php
}

// Change login logo link & title
add_filter('login_headerurl', function() {
    return home_url();
});
// โค้ดใหม่ (แก้ไขแล้ว)
add_filter('login_headertext', function() {
    return 'กลับไปที่เว็บไซต์ ' . get_bloginfo('name');
});

// Add back button to lost password form
add_action('login_footer', function () {
    if (isset($_GET['action']) && $_GET['action'] === 'lostpassword') {?>
        <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.location.search.includes("action=lostpassword")) {
                var formContainer = document.querySelector('form#lostpasswordform p.submit');
                if (formContainer) {
                    var backButton = document.createElement('a');
                    backButton.href = "<?php echo home_url('/' . (defined('CUSTOM_LOGIN_SLUG') ? CUSTOM_LOGIN_SLUG : 'fasicare-login')); ?>";
                    backButton.textContent = "Back";
                    backButton.className = "back-button";
                    var container = document.createElement('div');
                    container.className = "back-button-container";
                    container.appendChild(backButton);
                    container.appendChild(formContainer.querySelector('input[type="submit"]'));
                    formContainer.parentNode.replaceChild(container, formContainer);
                }
            }
        });
        </script>
        <style>
            .back-button-container { display: flex; justify-content: space-between; align-items: center; margin-top: 20px; gap: 10px;}
            .back-button { padding: 10px 20px; background: #0073aa; color: #fff; border-radius: 5px; font-weight: bold; text-decoration: none;}
            .back-button:hover { background: #005a87; }
            .back-button-container input[type="submit"] { margin: 0; font-weight: bold; }
        </style>
    <?php }
});

// Replace WP logo in admin bar with text
add_action('admin_head', function() {
    ?>
    <style>#wp-admin-bar-wp-logo{display:none!important;}#wp-admin-bar-custom-text{display:inline-block;font-weight:bold;color:#fff!important;font-size:16px;line-height:32px;}</style>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        var logoItem = document.getElementById('wp-admin-bar-wp-logo');
        if (logoItem) {
            var newText = document.createElement('li');
            newText.id = 'wp-admin-bar-custom-text';
            newText.innerHTML = '<?php echo esc_js(get_bloginfo("name")); ?>';
            logoItem.parentNode.replaceChild(newText, logoItem);
        }
    });
    </script>
    <?php
});

// Custom Welcome Panel
add_action('welcome_panel', 'gustabe_custom_welcome_panel');
function gustabe_custom_welcome_panel() {
    $current_user = wp_get_current_user();
    if ($current_user->exists()) {
        echo '<style>
        .welcome-panel-header {display: none;}
        .welcome-panel-content{min-height:auto;}
        .custom-welcome-panel-header_gustabe {font-size: 24px; font-weight: bold; color: #fff; padding: 20px; border-radius: 5px; text-align: center; margin: 20px 0;}
        .custom-welcome-panel-header_gustabe a {color: #fff; text-decoration: underline;}
        .custom-welcome-panel-header_gustabe a:hover {color: #ffcc00;}
        </style>';
        echo '<div class="custom-welcome-panel-header_gustabe">ยินดีต้อนรับ ' . esc_html($current_user->display_name) . ' สู่เว็บไซต์ <a href="'.esc_url(home_url()).'">' . esc_html(get_bloginfo('name')) . '</a></div>';
    }
}