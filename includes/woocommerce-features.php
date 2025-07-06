<?php
// ========== WOOCOMMERCE RELATED FEATURES ==========
// โค้ดส่วนนี้จะใส่ฟังก์ชันที่เกี่ยวข้องกับ WooCommerce ที่ต้องการปรับแต่ง
// เช่น การลบ meta boxes ที่เคยคอมเมนต์ไว้ใน functions.php เดิม

/*
add_action('add_meta_boxes', function () {
    remove_meta_box('postexcerpt', 'product', 'normal');
    remove_meta_box('slugdiv', 'product', 'normal');
    remove_meta_box('commentsdiv', 'product', 'normal');
    remove_meta_box('revisionsdiv', 'product', 'normal');
    remove_meta_box('postcustom', 'product', 'normal');
}, 99);
*/

// หากมีฟังก์ชัน WooCommerce อื่นๆ ที่จะเพิ่มในอนาคต สามารถเพิ่มที่นี่ได้
// เช่น custom checkout fields, product modifications, order processing hooks etc.