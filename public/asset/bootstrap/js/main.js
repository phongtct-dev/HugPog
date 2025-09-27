"use strict";

document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('main');
    const mainNav = document.querySelector('.main-nav');
    
    // --- 1. UTILITY: Chuẩn hóa URL để so sánh active link ---
    const getCleanUrlPath = () => {
        let path = window.location.pathname;
        const queryString = window.location.search;
        // Chuẩn hóa Trang Chủ: loại bỏ 'index.php/html' để URL chỉ còn '/public/'
        if (path.endsWith('index.php') || path.endsWith('index.html')) {
            path = path.substring(0, path.lastIndexOf('/') + 1);
        }
        return (path + queryString).replace(/\/+/g, '/').toLowerCase();
    };

    // --- 2. LOGIC: Đánh dấu Active Link ---
    const setActiveLinks = (selector) => {
        const container = document.querySelector(selector);
        if (!container) return;
        
        const currentUrl = getCleanUrlPath();
        container.querySelectorAll('li.active').forEach(li => li.classList.remove('active')); // Xóa active cũ

        container.querySelectorAll('li a').forEach(link => {
            // Chuẩn hóa href của link để so sánh
            let linkHref = new URL(link.href).pathname + new URL(link.href).search;
            if (linkHref.endsWith('index.php') || linkHref.endsWith('index.html')) {
                linkHref = linkHref.substring(0, linkHref.lastIndexOf('/') + 1);
            }
            linkHref = linkHref.replace(/\/+/g, '/').toLowerCase();

            // So sánh và set active
            if (currentUrl === linkHref && linkHref !== '') {
                const li = link.closest('li');
                li.classList.add('active');
                
                // Mở submenu cha nếu đây là link con trong sidebar
                if (selector === '.sidebar-menu') {
                    const parent = li.closest('.sidebar-item.has-sub');
                    if (parent) {
                         parent.classList.add('active');
                         parent.querySelector('.submenu').style.display = 'block'; 
                    }
                }
            }
        });
    };
    
    // --- 3. SIDEBAR & SUBMENU LOGIC ---
    
    // Submenu Toggle Event
    document.querySelectorAll('.sidebar-item.has-sub').forEach(item => {
        item.querySelector('.sidebar-link').addEventListener('click', e => {
            e.preventDefault();
            item.classList.toggle('active');
            slideToggle(item.querySelector('.submenu'), 300);
        });
    });

    // Sidebar/Main Content Toggle (Dùng cho nút Burger và nút ẩn)
    const toggleSidebar = () => {
        sidebar?.classList.toggle('active');
        mainContent?.classList.toggle('active'); // Tắt/mở lớp phủ (overlay) của main content trên mobile
    };
    document.querySelector('.burger-btn')?.addEventListener('click', toggleSidebar);
    document.querySelector('.sidebar-hide')?.addEventListener('click', toggleSidebar);

    // Logic Responsive: Tự động đóng sidebar trên mobile (< 1200px)
    const handleResize = () => {
        const isSmall = window.innerWidth < 1200;
        // Nếu màn hình nhỏ, loại bỏ 'active' (đóng sidebar); nếu màn hình lớn, thêm 'active' (mở sidebar)
        sidebar?.classList[isSmall ? 'remove' : 'add']('active');
        mainContent?.classList.remove('active'); // Đảm bảo main content không bị overlay trên desktop
    };
    handleResize(); // Khởi tạo ban đầu
    window.addEventListener('resize', handleResize); // Lắng nghe sự kiện resize
    
    // --- 4. KHỞI TẠO CHUNG KHÁC ---
    
    // 4.1. Khởi tạo Active Links khi tải trang
    setActiveLinks('.sidebar-menu');
    setActiveLinks('.main-nav');

    // 4.2. Mobile Nav Toggle (Từ code gốc, áp dụng cho responsive-nav)
    const responsiveNav = document.querySelector("#responsive-nav");
    if (responsiveNav) {
      document.querySelectorAll(".menu-toggle > a").forEach(el => {
        el.addEventListener("click", e => {
          e.preventDefault();
          responsiveNav.classList.toggle("active");
        });
      });
    }

    // 4.3. Slick Carousel Init (Khởi tạo thư viện Slick)
    // Cần đảm bảo thư viện jQuery và Slick đã được tải.
    const initSlick = (selector, settings) => {
        document.querySelectorAll(selector).forEach(el => {
            if (typeof $ !== 'undefined' && $.fn.slick) {
                $(el).slick(settings);
            }
        });
    };

    initSlick(".products-slick", {
        slidesToShow: 4,
        slidesToScroll: 1,
        autoplay: true,
        infinite: true,
        speed: 300,
        dots: false,
        arrows: true,
        appendArrows: $(document.querySelector(".products-slick").getAttribute("data-nav")),
        responsive: [
            { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 1 } },
            { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1 } },
        ],
    });

    initSlick(".products-widget-slick", {
        infinite: true,
        autoplay: true,
        speed: 300,
        dots: false,
        arrows: true,
        appendArrows: $(document.querySelector(".products-widget-slick").getAttribute("data-nav")),
    });


    // 4.4. Scrollbar & Scroll to Active Item (Perfect Scrollbar)
    if (typeof PerfectScrollbar === 'function') {
        const container = document.querySelector(".sidebar-wrapper");
        if (container) new PerfectScrollbar(container, { wheelPropagation: false });
    }
    // Cuộn đến mục menu đang active
    document.querySelector('.sidebar-item.active')?.scrollIntoView({ block: 'end' });
    
    // 4.5. Main Nav Click Handler (Xử lý active ngay lập tức khi click trên menu chính)
    mainNav?.addEventListener('click', e => {
        const link = e.target.closest('li a');
        if (link) {
            document.querySelectorAll('.main-nav li.active').forEach(li => li.classList.remove('active'));
            link.closest('li').classList.add('active');
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

/**
 * Hàm core thực hiện animation trượt (slide) bằng requestAnimationFrame.
 * Tái tạo lại hiệu ứng slide của jQuery mà không cần thư viện.
 * @param {HTMLElement} t - Phần tử DOM cần thực hiện hiệu ứng.
 * @param {number} e - Thời lượng animation (mặc định 400ms).
 * @param {function} o - Callback khi animation hoàn thành.
 * @param {boolean} i - true: slideDown (mở ra), false: slideUp (đóng lại).
 */
function _slide(t, e, o, i) {
    e = e || 400; i = i || !1; t.style.overflow = "hidden";
    if (i) t.style.display = "block"; // Nếu slideDown, đảm bảo hiển thị trước

    var p, 
        l = window.getComputedStyle(t), // Lấy thuộc tính CSS hiện tại
        n = parseFloat(l.getPropertyValue("height")),
        a = parseFloat(l.getPropertyValue("padding-top")),
        s = parseFloat(l.getPropertyValue("padding-bottom")),
        r = parseFloat(l.getPropertyValue("margin-top")),
        d = parseFloat(l.getPropertyValue("margin-bottom")),
        // Tính toán tốc độ thay đổi (giá trị / thời lượng)
        g = n / e, y = a / e, m = s / e, u = r / e, h = d / e;

    window.requestAnimationFrame(function animate(x) {
        if (void 0 === p) p = x;
        var f = x - p; // Thời gian đã trôi qua

        let props = {};
        if (i) { // Slide Down: tăng dần từ 0 lên giá trị ban đầu
            props.h = g * f + "px"; props.pt = y * f + "px"; props.pb = m * f + "px"; props.mt = u * f + "px"; props.mb = h * f + "px";
        } else { // Slide Up: giảm dần từ giá trị ban đầu về 0
            props.h = n - g * f + "px"; props.pt = a - y * f + "px"; props.pb = s - m * f + "px"; props.mt = r - u * f + "px"; props.mb = d - h * f + "px";
        }
        t.style.height = props.h; t.style.paddingTop = props.pt; t.style.paddingBottom = props.pb; 
        t.style.marginTop = props.mt; t.style.marginBottom = props.mb;
        
        if (f >= e) { // Animation hoàn thành
            t.style.cssText = ""; // Reset inline styles
            if (!i) t.style.display = "none"; // Nếu slideUp, ẩn phần tử
            if ("function" == typeof o) o();
        } else {
            window.requestAnimationFrame(animate);
        }
    });
}
/**
 * Hàm kiểm tra trạng thái và gọi _slide để chuyển trạng thái (Toggle)
 */
const slideToggle = (t, e, o) => { 0 === t.clientHeight ? _slide(t, e, o, !0) : _slide(t, e, o) };

// =========================================================================
// LOGIC JQUERY (SLICK & ZOOM) - CHẠY KHI JQUERY ĐÃ SẴN SÀNG
// =========================================================================

// Khối code này chạy khi toàn bộ DOM đã sẵn sàng VÀ jQuery đã tải xong, 
// đảm bảo $ luôn là function của jQuery, khắc phục lỗi.
if (typeof jQuery !== 'undefined') {
    jQuery(function($) {

        // --- KHỞI TẠO SLICK CHUNG ---
        
        $('.products-slick').each(function() {
            const nav = $(this).attr('data-nav');
            $(this).slick({
                slidesToShow: 4,
                slidesToScroll: 1,
                autoplay: true,
                infinite: true,
                speed: 300,
                dots: false,
                arrows: true,
                appendArrows: nav ? $(nav) : false,
                responsive: [
                    { breakpoint: 991, settings: { slidesToShow: 2, slidesToScroll: 1 } },
                    { breakpoint: 480, settings: { slidesToShow: 1, slidesToScroll: 1 } },
                ],
            });
        });

        $('.products-widget-slick').each(function() {
            const nav = $(this).attr('data-nav');
            $(this).slick({
                infinite: true,
                autoplay: true,
                speed: 300,
                dots: false,
                arrows: true,
                appendArrows: nav ? $(nav) : false,
            });
        });

        // --- KHỞI TẠO CHỨC NĂNG CHI TIẾT SẢN PHẨM ---

        // 1. Slider Ảnh Chính
        $('#product-main-img').slick({
            infinite: true,
            speed: 300,
            dots: false,
            arrows: true,
            fade: true,
            asNavFor: '#product-imgs',
        });

        // 2. Slider Ảnh Thu Nhỏ (Thumbnail)
        $('#product-imgs').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            arrows: true,
            centerMode: true,
            focusOnSelect: true,
            centerPadding: '0px',
            vertical: true, // CUỘN DỌC
            asNavFor: '#product-main-img',
            responsive: [{
                breakpoint: 991,
                settings: {
                    vertical: false,
                    arrows: false,
                    dots: true,
                }
            }]
        });

        // 3. Khởi tạo Zoom (Sử dụng plugin jQuery Zoom hoặc ElevateZoom)
        var $mainImgSlider = $('#product-main-img');
        
        // Kiểm tra plugin zoom tồn tại
        if ($mainImgSlider.length && typeof $.fn.zoom !== 'undefined') {
            
            // Khởi tạo Zoom cho tất cả ảnh trong slide
            $mainImgSlider.find('.product-preview').zoom(); 
            
            // Tái khởi tạo Zoom khi slide chuyển đổi
            $mainImgSlider.on('afterChange', function(event, slick, currentSlide){
                 // Hủy zoom cũ trên tất cả các ảnh trước
                 $mainImgSlider.find('.product-preview').trigger('zoom.destroy');
                
                 // Khởi tạo zoom trên slide hiện tại
                 $mainImgSlider.find('.product-preview').eq(currentSlide).zoom();
            });
        }
        
    }); // END jQuery(function($)
} else {
    console.error("Lỗi: Không tìm thấy thư viện jQuery. Vui lòng kiểm tra lại đường dẫn file.");
}