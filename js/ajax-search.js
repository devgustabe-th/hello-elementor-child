document.addEventListener('DOMContentLoaded', function() {
    const searchModal = document.getElementById('search-popup-modal');
    if (!searchModal) return;

    const searchInput = searchModal.querySelector('.search-input-field');
    const searchFilterButtons = searchModal.querySelector('.search-filter-buttons');
    const searchResultsContainer = searchModal.querySelector('.ajax-search-results');
    const loadingIndicator = searchModal.querySelector('.loading-indicator');
    const noResultsMessage = searchModal.querySelector('.no-results-message');

    let typingTimer;
    const doneTypingInterval = 500; // หน่วงเวลา 500 มิลลิวินาทีก่อนส่งคำขอ AJAX

    // ฟังก์ชันสำหรับส่งคำขอ AJAX ค้นหา
    function performAjaxSearch() {
        clearTimeout(typingTimer); // ล้าง Timer เก่า
        const searchTerm = searchInput.value.trim();
        const selectedType = searchFilterButtons ? searchFilterButtons.querySelector('input[name="search_type"]:checked').value : 'all';

        searchResultsContainer.innerHTML = ''; // ล้างผลลัพธ์เก่าออก
        noResultsMessage.style.display = 'none'; // ซ่อนข้อความไม่พบผลลัพธ์

        if (searchTerm.length < 2) { // ค้นหาเมื่อมีอย่างน้อย 2 ตัวอักษร
            loadingIndicator.style.display = 'none';
            return;
        }

        loadingIndicator.style.display = 'block'; // แสดงสถานะกำลังค้นหา

        // สร้างข้อมูลสำหรับส่งในคำขอ AJAX
        const data = new URLSearchParams();
        data.append('action', 'hello_elementor_child_ajax_search'); // WordPress AJAX action ที่จะใช้
        data.append('s', searchTerm);
        data.append('search_type', selectedType);
        // เพิ่ม Nonce เพื่อความปลอดภัย (ต้องส่งมาจาก functions.php)
        if (typeof hello_elementor_child_ajax !== 'undefined' && hello_elementor_child_ajax.nonce) {
            data.append('security', hello_elementor_child_ajax.nonce);
        }

        // ส่งคำขอ AJAX ด้วย Fetch API
        fetch(hello_elementor_child_ajax.ajax_url, { // URL ของ admin-ajax.php (ต้องส่งมาจาก functions.php)
            method: 'POST',
            body: data
        })
        .then(response => response.json())
        .then(results => {
            loadingIndicator.style.display = 'none'; // ซ่อนสถานะกำลังค้นหา
            searchResultsContainer.innerHTML = ''; // ล้างผลลัพธ์ก่อนเพิ่มใหม่

            if (results.length > 0) {
                results.forEach(item => {
                    const resultItem = document.createElement('div');
                    resultItem.classList.add('ajax-search-result-item', 'mb-2', 'p-2', 'border', 'rounded');

                    let thumbnailUrl = '';
                    if (item.thumbnail) {
                        thumbnailUrl = `<div class="thumbnail me-2"><img src="${item.thumbnail}" alt="${item.title}" style="width:50px; height:auto; object-fit:cover;"></div>`;
                    }

                    resultItem.innerHTML = `
                        <a href="${item.link}" class="d-flex align-items-center text-decoration-none">
                            ${thumbnailUrl}
                            <div>
                                <h6 class="mb-0 text-dark">${item.title}</h6>
                                <p class="text-muted small mb-0">${item.type_label}</p>
                            </div>
                        </a>
                    `;
                    searchResultsContainer.appendChild(resultItem);
                });
            } else {
                noResultsMessage.style.display = 'block'; // แสดงข้อความไม่พบผลลัพธ์
            }
        })
        .catch(error => {
            console.error('Error fetching search results:', error);
            loadingIndicator.style.display = 'none';
            // สามารถเพิ่มข้อความแสดงข้อผิดพลาดให้ผู้ใช้เห็นได้ที่นี่
        });
    }

    // Event listener สำหรับช่องค้นหา (พร้อม Debouncing)
    searchInput.addEventListener('keyup', function() {
        clearTimeout(typingTimer); // ล้าง Timer เก่าทุกครั้งที่มีการพิมพ์
        typingTimer = setTimeout(performAjaxSearch, doneTypingInterval); // ตั้ง Timer ใหม่
    });

    // Event listener สำหรับการเปลี่ยนตัวเลือก Filter
    if (searchFilterButtons) {
        searchFilterButtons.addEventListener('change', function() {
            performAjaxSearch(); // เรียกค้นหาใหม่ทันทีเมื่อเปลี่ยน Filter
        });
    }

    // Auto-focus ช่องค้นหาเมื่อ Modal เปิดขึ้นมา
    searchModal.addEventListener('shown.bs.modal', function () {
        searchInput.focus();
    });

    // ล้างผลลัพธ์เมื่อ Modal ถูกปิด
    searchModal.addEventListener('hidden.bs.modal', function () {
        searchInput.value = ''; // ล้างช่องค้นหา
        searchResultsContainer.innerHTML = ''; // ล้างผลลัพธ์
        noResultsMessage.style.display = 'none'; // ซ่อนข้อความไม่พบผลลัพธ์
        loadingIndicator.style.display = 'none'; // ซ่อนสถานะกำลังโหลด
        // รีเซ็ต Radio Button ให้เลือก 'ทุกอย่าง'
        const allRadioButton = searchFilterButtons.querySelector('#searchTypeAll');
        if (allRadioButton) {
            allRadioButton.checked = true;
        }
    });
});