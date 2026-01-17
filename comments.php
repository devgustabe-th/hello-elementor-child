<?php
/**
 * The template for displaying the list of comments and the comment form.
 *
 * @package HelloElementor
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

if ( ! post_type_supports( get_post_type(), 'comments' ) ) {
    return;
}

if ( ! have_comments() && ! comments_open() ) {
    return;
}

// Comment Reply Script.
if ( comments_open() && get_option( 'thread_comments' ) ) {
    wp_enqueue_script( 'comment-reply' );
}
?>

<section id="comments" class="comments-area">

    <?php if ( have_comments() ) : ?>
        <h2 class="title-comments">
            <?php
            $comments_number = get_comments_number();
            if ( '1' === $comments_number ) {
                printf( esc_html_x( 'One Response', 'comments title', 'hello-elementor' ) );
            } else {
                printf(
                    esc_html(
                        _nx(
                            '%s Response',
                            '%s Responses',
                            $comments_number,
                            'comments title',
                            'hello-elementor'
                        )
                    ),
                    esc_html( number_format_i18n( $comments_number ) )
                );
            }
            ?>
        </h2>

        <?php the_comments_navigation(); ?>

        <ol class="comment-list comment-list-styled">
            <?php
            wp_list_comments(
                [
                    'style'       => 'ol',
                    'short_ping'  => true,
                    'avatar_size' => 42,
                ]
            );
            ?>
        </ol>

        <?php the_comments_navigation(); ?>

    <?php endif; ?>

    <?php
    // กำหนดค่า arguments สำหรับฟอร์มคอมเมนต์
    $comments_args = array(
        'title_reply_before' => '<h2 id="reply-title" class="comment-reply-title">',
        'title_reply_after'  => '</h2>',
        'class_submit'       => 'submit-comment-button',
        'comment_field'      => '
            <p class="comment-form-comment">
                <textarea id="comment" name="comment" placeholder="Write your comment here..." aria-required="true"></textarea>
            </p>
            <p class="comment-form-honeypot" style="display:none;">
                <label for="honeypot">Leave this field empty</label>
                <input type="text" name="honeypot" id="honeypot" />
            </p>
        ',
        'fields'             => array(
            'author' => '<p class="comment-form-author" style="width: 23%; float: left; margin-right: 2%;"><label for="author">' . __( 'Your Name' ) . '</label><input id="author" name="author" type="text" placeholder="Name" required="required" /></p>',
            'email'  => '<p class="comment-form-email" style="width: 23%; float: left; margin-right: 2%;"><label for="email">' . __( 'Your Email' ) . '</label><input id="email" name="email" type="email" placeholder="Email" required="required" /></p>',
            'phone'  => '<p class="comment-form-phone" style="width: 23%; float: left; margin-right: 2%;"><label for="phone">' . __( 'Your Phone (optional)' ) . '</label><input id="phone" name="phone" type="text" placeholder="Phone" /></p>',
            'lineuser' => '<p class="comment-form-lineuser" style="width: 23%; float: left;"><label for="lineuser">' . __( 'Line User (optional)' ) . '</label><input id="lineuser" name="lineuser" type="text" placeholder="Line ID" /></p>',
        ),
    );

    comment_form( $comments_args );
    ?>

</section>

<?php
// ฟังก์ชันกรอง Honeypot
function check_comment_honeypot() {
    if ( ! empty( $_POST['honeypot'] ) ) {
        wp_die( __( 'Spam detected. Your comment has been blocked.', 'hello-elementor' ) );
    }
}
add_action( 'pre_comment_on_post', 'check_comment_honeypot' );
?>
