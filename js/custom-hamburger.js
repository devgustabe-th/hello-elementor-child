const nav = document.querySelector(".nav-container");

if (nav) {
  // เพิ่มโค้ดส่วนนี้เพื่อบังคับขนาดของ nav-toggle
  const navToggle = nav.querySelector(".nav-toggle");
  if (navToggle) {
    navToggle.style.width = "40px";
    navToggle.style.height = "40px";
    navToggle.style.display = "block"; // ตรวจสอบให้แน่ใจว่าเป็น block
    navToggle.style.position = "absolute"; // ตรวจสอบให้แน่ใจว่าเป็น absolute
    navToggle.style.top = "10px"; // ตำแหน่งเดิม
    navToggle.style.left = "10px"; // ตำแหน่งเดิม
    navToggle.style.zIndex = "2"; // z-index
  }
  // สิ้นสุดโค้ดส่วนบังคับขนาด

  const toggle = nav.querySelector(".nav-toggle");
  
  if (toggle) {
    toggle.addEventListener("click", () => {
      if (nav.classList.contains("is-active")) {
        nav.classList.remove("is-active");
      }
      else {
        nav.classList.add("is-active");
      }
    });
    
    // หากต้องการให้เมนูปิดเมื่อคลิกนอกพื้นที่ (เหมือน blur)
    // nav.addEventListener("blur", () => {
    //   nav.classList.remove("is-active");
    // });
    // Note: blur event on div can be tricky for accessibility.
    // Consider adding a click listener to the document body that closes the menu.
  }
}