jQuery(document).ready(function($) {
    // เมื่อ Quick Edit ถูกคลิก
    $('a.editinline').on('click', function() {
        // ดึง post ID ของแถวที่ถูกแก้ไข
        var post_id = $(this).closest('tr').attr('id').replace("post-", "");

        // ดึงค่าของราคาปกติและราคาลดจาก data attribute ที่ใส่ไว้ในแต่ละแถว
        var regular_price = $('#_regular_price-' + post_id).text();
        var sale_price = $('#_sale_price-' + post_id).text();

        // เติมค่าลงในฟิลด์ของ Quick Edit
        $('input[name="_regular_price"]').val(regular_price);
        $('input[name="_sale_price"]').val(sale_price);
    });
});
