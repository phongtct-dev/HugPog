"use strict";

document.addEventListener("DOMContentLoaded", () => {
  const sidebar = document.getElementById("sidebar");
  const mainContent = document.getElementById("main");
  const mainNav = document.querySelector(".main-nav");

  // --- 1. UTILITY: Chuẩn hóa URL để so sánh active link ---
  const getCleanUrlPath = () => {
    let path = window.location.pathname;
    const queryString = window.location.search;
    // Chuẩn hóa Trang Chủ: loại bỏ 'index.php/html' để URL chỉ còn '/public/'
    if (path.endsWith("index.php") || path.endsWith("index.html")) {
      path = path.substring(0, path.lastIndexOf("/") + 1);
    }
    return (path + queryString).replace(/\/+/g, "/").toLowerCase();
  };

  // --- 2. LOGIC: Đánh dấu Active Link ---
  const setActiveLinks = (selector) => {
    const container = document.querySelector(selector);
    if (!container) return;

    const currentUrl = getCleanUrlPath();
    container
      .querySelectorAll("li.active")
      .forEach((li) => li.classList.remove("active")); // Xóa active cũ

    container.querySelectorAll("li a").forEach((link) => {
      // Chuẩn hóa href của link để so sánh
      let linkHref = new URL(link.href).pathname + new URL(link.href).search;
      if (linkHref.endsWith("index.php") || linkHref.endsWith("index.html")) {
        linkHref = linkHref.substring(0, linkHref.lastIndexOf("/") + 1);
      }
      linkHref = linkHref.replace(/\/+/g, "/").toLowerCase();

      // So sánh và set active
      if (currentUrl === linkHref && linkHref !== "") {
        const li = link.closest("li");
        li.classList.add("active");

        // Mở submenu cha nếu đây là link con trong sidebar
        if (selector === ".sidebar-menu") {
          const parent = li.closest(".sidebar-item.has-sub");
          if (parent) {
            parent.classList.add("active");
            parent.querySelector(".submenu").style.display = "block";
          }
        }
      }
    });
  };

  // --- 3. SIDEBAR & SUBMENU LOGIC ---

  // Submenu Toggle Event
  document.querySelectorAll(".sidebar-item.has-sub").forEach((item) => {
    item.querySelector(".sidebar-link").addEventListener("click", (e) => {
      e.preventDefault();
      item.classList.toggle("active");
      slideToggle(item.querySelector(".submenu"), 300);
    });
  });

  // Sidebar/Main Content Toggle (Dùng cho nút Burger và nút ẩn)
  const toggleSidebar = () => {
    sidebar?.classList.toggle("active");
    mainContent?.classList.toggle("active"); // Tắt/mở lớp phủ (overlay) của main content trên mobile
  };
  document
    .querySelector(".burger-btn")
    ?.addEventListener("click", toggleSidebar);
  document
    .querySelector(".sidebar-hide")
    ?.addEventListener("click", toggleSidebar);

  // Logic Responsive: Tự động đóng sidebar trên mobile (< 1200px)
  const handleResize = () => {
    const isSmall = window.innerWidth < 1200;
    // Nếu màn hình nhỏ, loại bỏ 'active' (đóng sidebar); nếu màn hình lớn, thêm 'active' (mở sidebar)
    sidebar?.classList[isSmall ? "remove" : "add"]("active");
    mainContent?.classList.remove("active"); // Đảm bảo main content không bị overlay trên desktop
  };
  handleResize(); // Khởi tạo ban đầu
  window.addEventListener("resize", handleResize); // Lắng nghe sự kiện resize

  // --- 4. KHỞI TẠO CHUNG KHÁC ---

  // 4.1. Khởi tạo Active Links khi tải trang
  setActiveLinks(".sidebar-menu");
  setActiveLinks(".main-nav");

  // 4.2. Mobile Nav Toggle (Từ code gốc, áp dụng cho responsive-nav)
  const responsiveNav = document.querySelector("#responsive-nav");
  if (responsiveNav) {
    document.querySelectorAll(".menu-toggle > a").forEach((el) => {
      el.addEventListener("click", (e) => {
        e.preventDefault();
        responsiveNav.classList.toggle("active");
      });
    });
  }

  // 4.4. Scrollbar & Scroll to Active Item (Perfect Scrollbar)
  if (typeof PerfectScrollbar === "function") {
    const container = document.querySelector(".sidebar-wrapper");
    if (container) new PerfectScrollbar(container, { wheelPropagation: false });
  }
  // Cuộn đến mục menu đang active
  document
    .querySelector(".sidebar-item.active")
    ?.scrollIntoView({ block: "end" });

  // 4.5. Main Nav Click Handler (Xử lý active ngay lập tức khi click trên menu chính)
  mainNav?.addEventListener("click", (e) => {
    const link = e.target.closest("li a");
    if (link) {
      document
        .querySelectorAll(".main-nav li.active")
        .forEach((li) => li.classList.remove("active"));
      link.closest("li").classList.add("active");
    }
  });

});

function showSubscriptionAlert() {
  // Lấy đối tượng form (nếu bạn cần reset form)
  const form = document.getElementById("newsletter-form");

  // 1. Hiển thị thông báo cho người dùng
  alert("Đăng ký nhận thông báo thành công! Cảm ơn bạn.");

  // 2. Tùy chọn: Reset form để xóa email đã nhập
  if (form) {
    form.reset();
  }
}

