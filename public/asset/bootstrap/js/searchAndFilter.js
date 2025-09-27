document.addEventListener("DOMContentLoaded", () => {
    // Dữ liệu sản phẩm mẫu, bạn có thể thay thế bằng dữ liệu từ API hoặc database
    const products = [
        { id: 1, name: "Laptop ABC", category: "Laptop", brand: "Apple", price: 15000000, oldPrice: 18000000, rating: 4, sale: 20, new: true, image: "../public/asset/image/product01.png" },
        { id: 2, name: "Smartphone XYZ", category: "Điện thoại", brand: "Samsung", price: 75000000, oldPrice: 85000000, rating: 5, sale: 15, new: false, image: "../public/asset/image/product02.png" },
        { id: 3, name: "Camera Pro 500", category: "Máy ảnh", brand: "Sony", price: 11500000, oldPrice: 13000000, rating: 4, sale: 10, new: true, image: "../public/asset/image/product03.png" },
        { id: 4, name: "Tai nghe Bluetooth", category: "Phụ kiện", brand: "LG", price: 1500000, oldPrice: 1800000, rating: 3, sale: 10, new: true, image: "../public/asset/image/product04.png" },
        { id: 5, name: "Laptop Gaming Pro", category: "Laptop", brand: "LG", price: 18000000, oldPrice: 20000000, rating: 5, sale: 10, new: false, image: "../public/asset/image/product05.png" },
        { id: 6, name: "iPhone 14", category: "Điện thoại", brand: "Apple", price: 12000000, oldPrice: 13500000, rating: 5, sale: 10, new: false, image: "../public/asset/image/product06.png" },
        { id: 7, name: "Galaxy S22", category: "Điện thoại", brand: "Samsung", price: 9000000, oldPrice: 10000000, rating: 4, sale: 10, new: true, image: "../public/asset/image/product07.png" },
        { id: 8, name: "Camera Canon", category: "Máy ảnh", brand: "Sony", price: 8500000, oldPrice: 9500000, rating: 4, sale: 10, new: false, image: "../public/asset/image/product08.png" },
    ];

    // --- KHAI BÁO BIẾN DOM ---
    const productListContainer = document.getElementById("product-list");
    const searchInput = document.getElementById("search-input");
    const searchCategory = document.getElementById("search-category");
    const categoryCheckboxes = document.querySelectorAll('.checkbox-filter input[id^="category-"]');
    const brandCheckboxes = document.querySelectorAll('.checkbox-filter input[id^="brand-"]');
    const priceRangeInput = document.getElementById("price-range");
    const priceValueSpan = document.getElementById("price-value");
    const applyButton = document.getElementById("apply-filters-btn"); // Nút xác nhận

    // --- HÀM RENDER SẢN PHẨM ---
    const renderProducts = (productsToRender) => {
        productListContainer.innerHTML = "";
        
        if (productsToRender.length === 0) {
            productListContainer.innerHTML = '<div class="col-12"><p>Không tìm thấy sản phẩm nào phù hợp.</p></div>';
            return;
        }

        productsToRender.forEach((product) => {
             const productHtml = `
              <div class="col-md-4 col-sm-6 col-6">
                <div class="product">
                  <div class="product-img">
                    <img src="${product.image}" alt="${product.name}" />
                    <div class="product-label">
                      ${product.sale ? `<span class="sale">-${product.sale}%</span>` : ''}
                      ${product.new ? `<span class="new">MỚI</span>` : ''}
                    </div>
                  </div>
                  <div class="product-body">
                    <p class="product-category">${product.category}</p>
                    <h3 class="product-name"><a href="#">${product.name}</a></h3>
                    <h4 class="product-price">${product.price.toLocaleString('vi-VN')}₫ <del class="product-old-price">${product.oldPrice.toLocaleString('vi-VN')}₫</del></h4>
                    <div class="product-rating">
                      ${'<i class="fa fa-star me-2"></i>'.repeat(product.rating)}
                      ${'<i class="fa fa-star-o"></i>'.repeat(5 - product.rating)}
                    </div>
                    <div class="product-btns">
                      <button class="add-to-wishlist"><i class="fa-solid fa-heart"></i><span class="tooltipp">Thêm vào Yêu thích</span></button>
                      <button class="add-to-compare"><i class="fa fa-exchange"></i><span class="tooltipp">So sánh</span></button>
                      <button class="quick-view"><a href="../public/product_detail.php"><i class="fa fa-eye"></i></a><span class="tooltipp">Xem nhanh</span></button>
                    </div>
                  </div>
                  <div class="add-to-cart">
                    <button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> Thêm vào Giỏ</button>
                  </div>
                </div>
              </div>
            `;
            productListContainer.innerHTML += productHtml;
        });
    };

    // --- HÀM LỌC SẢN PHẨM CHUNG ---
    const filterProducts = () => {
        const keyword = searchInput ? searchInput.value.trim() : "";
        
        const selectedCategories = Array.from(categoryCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        const selectedBrands = Array.from(brandCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);

        const maxPrice = priceRangeInput ? parseInt(priceRangeInput.value) : 200000000;

        const filtered = products.filter(product => {
            const keywordMatch = product.name.toLowerCase().includes(keyword.toLowerCase());
            const categoryMatch = selectedCategories.length === 0 || selectedCategories.includes(product.category);
            const brandMatch = selectedBrands.length === 0 || selectedBrands.includes(product.brand);
            const priceMatch = product.price <= maxPrice;
            
            return keywordMatch && categoryMatch && brandMatch && priceMatch;
        });

        renderProducts(filtered);
    };
    
    // --- HÀM CHỈ CẬP NHẬT GIÁ TRỊ RANGE (KHÔNG GỌI HÀM LỌC) ---
    const updatePriceDisplay = () => {
        if (priceRangeInput && priceValueSpan) {
            priceValueSpan.textContent = `${parseInt(priceRangeInput.value).toLocaleString('vi-VN')}₫`;
        }
    }
    
    // ----------------------------------------------------------------------------------
    // ĐIỂM QUAN TRỌNG: CHỈ NÚT ÁP DỤNG GỌI HÀM filterProducts()
    // ----------------------------------------------------------------------------------
    
    // 1. Gắn sự kiện cho NÚT ÁP DỤNG (Xác nhận) - NƠI DUY NHẤT LỌC DỮ LIỆU BỘ LỌC BÊN
    if (applyButton) {
        applyButton.addEventListener("click", () => {
            filterProducts(); // Áp dụng tất cả bộ lọc
        });
    }

    // 2. Lắng nghe range input (CHỈ để cập nhật text)
    if (priceRangeInput) {
        priceRangeInput.addEventListener("input", updatePriceDisplay);
    }
    
    // 3. Lắng nghe search input (Dùng cho thanh tìm kiếm trên header, giữ nguyên logic chuyển trang)
    const searchForm = document.getElementById("search-form");
    if (searchForm) {
      searchForm.addEventListener("submit", (e) => {
        e.preventDefault(); 
        const keyword = searchInput.value.trim();
        const category = searchCategory.value;
        const url = `../public/product_list.php?keyword=${encodeURIComponent(keyword)}&category=${encodeURIComponent(category)}`;
        window.location.href = url;
      });
    }

    // ***KHÔNG CÓ LẮNG NGHE SỰ KIỆN NÀO CHO CHECKBOX VÀ SEARCH INPUT CỦA BỘ LỌC BÊN***

    // --- XỬ LÝ LỌC BAN ĐẦU KHI TẢI TRANG (1 LẦN) ---
    const urlParams = new URLSearchParams(window.location.search);
    const keywordFromUrl = urlParams.get('keyword');
    const categoryFromUrl = urlParams.get('category');
    
    // Đặt giá trị cho các input từ URL
    if (searchInput && keywordFromUrl) {
      searchInput.value = keywordFromUrl;
    }
    
    // Tự động check checkbox nếu có tham số category từ URL
    if (categoryFromUrl) {
      const categoryCheckbox = document.querySelector(`.checkbox-filter input[value="${categoryFromUrl}"]`);
      if (categoryCheckbox) {
        categoryCheckbox.checked = true;
      }
    }
    
    // Cập nhật giá trị hiển thị ban đầu
    updatePriceDisplay(); 

    // GỌI HÀM LỌC LẦN ĐẦU TIÊN (Sau khi đã thiết lập trạng thái ban đầu)
    filterProducts();
});
