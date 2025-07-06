<?php
// ========== SVG UPLOAD & SANITIZE ==========
add_filter('upload_mimes', function ($file_types) {
    if (current_user_can('administrator')) {
        $file_types['svg'] = 'image/svg+xml';
    }
    return $file_types;
});

add_filter('wp_handle_upload_prefilter', 'gustabe_sanitize_svg');
function gustabe_sanitize_svg($file) {
    if (($file['type'] ?? '') === 'image/svg+xml') {
        $svg_content = file_get_contents($file['tmp_name']);
        libxml_use_internal_errors(true);
        $dom = new DOMDocument();
        if ($dom->loadXML($svg_content, LIBXML_NOENT | LIBXML_DTDLOAD | LIBXML_DTDATTR)) {
            $scripts = $dom->getElementsByTagName('script');
            while ($scripts->length > 0) {
                $scripts->item(0)->parentNode->removeChild($scripts->item(0));
            }
            $cleaned_svg = $dom->saveXML();
            file_put_contents($file['tmp_name'], $cleaned_svg);
        } else {
            wp_die(__('SVG file contains potential security risks.'));
        }
        libxml_clear_errors();
    }
    return $file;
}

// Inline SVG logo
add_filter('get_custom_logo', 'gustabe_inline_svg_custom_logo');
function gustabe_inline_svg_custom_logo($html) {
    $custom_logo_id = get_theme_mod('custom_logo');
    $logo_url = get_attached_file($custom_logo_id);
    if ($custom_logo_id && pathinfo($logo_url, PATHINFO_EXTENSION) === 'svg') {
        $svg_content = file_get_contents($logo_url);
        if ($svg_content) {
            $width = '25';
            $height = '25';
            $style = 'border-radius:0.2em;overflow:hidden;';
            $svg_content = preg_replace('/<svg([^>]*?)>/', '<svg width="' . esc_attr($width) . '" height="' . esc_attr($height) . '" style="' . esc_attr($style) . '" $1>', $svg_content);
            $html = $svg_content;
        }
    }
    return $html;
}