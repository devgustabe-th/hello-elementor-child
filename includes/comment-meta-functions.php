<?php
// ========== COMMENT META: PHONE & LINE ==========
add_action('comment_post', 'gustabe_save_comment_meta_data');
function gustabe_save_comment_meta_data($comment_id) {
    if (isset($_POST['phone'])) {
        $phone = sanitize_text_field($_POST['phone']);
        add_comment_meta($comment_id, 'phone', $phone);
    }
    if (isset($_POST['lineuser'])) {
        $lineuser = sanitize_text_field($_POST['lineuser']);
        add_comment_meta($comment_id, 'lineuser', $lineuser);
    }
}

add_filter('manage_edit-comments_columns', function ($columns) {
    $columns['phone'] = 'Phone';
    $columns['lineuser'] = 'Line User';
    return $columns;
});

add_action('manage_comments_custom_column', function ($column, $comment_id) {
    if ($column === 'phone') {
        echo esc_html(get_comment_meta($comment_id, 'phone', true));
    }
    if ($column === 'lineuser') {
        echo esc_html(get_comment_meta($comment_id, 'lineuser', true));
    }
}, 10, 2);

add_filter('comment_text', function ($comment_text) {
    if (current_user_can('administrator')) {
        $phone = get_comment_meta(get_comment_ID(), 'phone', true);
        $lineuser = get_comment_meta(get_comment_ID(), 'lineuser', true);
        if (!empty($phone)) $comment_text .= '<p><strong>Phone:</strong> ' . esc_html($phone) . '</p>';
        if (!empty($lineuser)) $comment_text .= '<p><strong>Line User:</strong> ' . esc_html($lineuser) . '</p>';
    }
    return $comment_text;
});