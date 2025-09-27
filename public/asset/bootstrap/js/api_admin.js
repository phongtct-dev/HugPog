document.addEventListener("DOMContentLoaded", function () {
    
    // =======================================================================
    // HÀM HỖ TRỢ CHUNG
    // =======================================================================

    /**
     * Định dạng số thành chuỗi tiền tệ VNĐ có dấu chấm phân cách hàng nghìn.
     * @param {number} amount - Giá trị số cần định dạng.
     * @returns {string} Chuỗi tiền tệ (ví dụ: "123.456 VNĐ").
     */
    const formatCurrencyVN = (amount) => {
        // Sử dụng Intl.NumberFormat cho định dạng chuẩn Việt Nam
        return new Intl.NumberFormat('vi-VN', { 
            style: 'currency', 
            currency: 'VND',
            minimumFractionDigits: 0, // Không hiển thị số lẻ
            maximumFractionDigits: 0
        }).format(amount).replace('₫', ' VNĐ'); // Thay ký hiệu ₫ bằng VNĐ (tùy chọn)
    };
    
    // =======================================================================
    // 1. BIỂU ĐỒ TỔNG QUAN BÁN HÀNG (SALES OVERVIEW)
    // =======================================================================
    // Giữ nguyên dữ liệu, chỉ dịch labels
    var options_sales_overview = {
        series: [
            {
                name: "Quản trị A", // Dịch: Ample Admin
                data: [355, 390, 300, 350, 390, 180],
            },
            {
                name: "Quản trị B", // Dịch: Pixel Admin
                data: [280, 250, 325, 215, 250, 310],
            },
        ],
        chart: {
            type: "bar",
            height: 275,
            toolbar: {
                show: false,
            },
            foreColor: "#adb0bb",
            fontFamily: "inherit",
            sparkline: {
                enabled: false,
            },
        },
        grid: {
            show: false,
            borderColor: "transparent",
            padding: {
                left: 0,
                right: 0,
                bottom: 0,
            },
        },
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: "25%",
                endingShape: "rounded",
                borderRadius: 5,
            },
        },
        colors: ["#3e81f5", "#5c6d7d"],
        dataLabels: {
            enabled: false,
        },
        yaxis: {
            show: true,
            min: 100,
            max: 400,
            tickAmount: 3,
        },
        stroke: {
            show: true,
            width: 5,
            lineCap: "butt",
            colors: ["transparent"],
        },
        xaxis: {
            type: "category",
            // Dịch: ["Mon", "Tue", "Wed", "Thu", "Fri", "Sat"]
            categories: ["T2", "T3", "T4", "T5", "T6", "T7"], 
            axisBorder: {
                show: false,
            },
        },
        fill: {
            opacity: 1,
        },
        tooltip: {
            theme: "dark",
        },
        legend: {
            show: false,
        },
    };

    var chart_column_basic = new ApexCharts(
        document.querySelector("#sales-overview"),
        options_sales_overview
    );
    chart_column_basic.render();

    // =======================================================================
    // 2. BIỂU ĐỒ DOANH THU THEO THÁNG (REVENUE CHART)
    // =======================================================================
    var options_revenue_chart = {
        series: [
            {
                name: "Doanh thu (Triệu VNĐ)", // Cập nhật tên đơn vị
                // Chuyển dữ liệu sang VNĐ (Giả định đơn vị cũ là nghìn USD, Tỷ lệ 1 USD = 25.000 VNĐ)
                // Ví dụ: 30 (nghìn USD) -> 750 (triệu VNĐ)
                // Giữ nguyên các giá trị cũ và coi chúng là đơn vị (Triệu VNĐ) cho mục đích hiển thị
                data: [30, 40, 45, 50, 49, 60, 70, 91, 125, 110, 140, 150],
            },
        ],
        chart: {
            height: 350,
            type: "bar",
            toolbar: {
                show: false,
            },
        },
        plotOptions: {
            bar: {
                borderRadius: 10,
                dataLabels: {
                    position: "top", // top, center, bottom
                },
            },
        },
        dataLabels: {
            enabled: true,
            // Cập nhật formatter để hiển thị VNĐ
            formatter: function (val) {
                // Giá trị cũ đang là số nguyên, ta dùng hàm định dạng VNĐ cho gọn
                // Cần nhân thêm 1,000,000 nếu muốn hiển thị chính xác Triệu VNĐ
                return formatCurrencyVN(val * 1000000); // Giả định đơn vị là Triệu VNĐ
            },
            offsetY: -20,
            style: {
                fontSize: "12px",
                colors: ["#304758"],
            },
        },
        xaxis: {
            // Dịch các tháng sang tiếng Việt
            categories: [
                "Th1", "Th2", "Th3", "Th4", "Th5", "Th6",
                "Th7", "Th8", "Th9", "Th10", "Th11", "Th12",
            ],
            position: "bottom",
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            crosshairs: {
                fill: {
                    type: "gradient",
                    gradient: {
                        colorFrom: "#D8E3F0",
                        colorTo: "#BED1E6",
                        stops: [0, 100],
                        opacityFrom: 0.4,
                        opacityTo: 0.5,
                    },
                },
            },
            tooltip: {
                enabled: false,
            },
        },
        yaxis: {
            axisBorder: {
                show: false,
            },
            axisTicks: {
                show: false,
            },
            labels: {
                show: false,
                // Cập nhật formatter yaxis nếu có
                formatter: function (val) {
                    return formatCurrencyVN(val * 1000000);
                },
            },
        },
        title: {
            floating: true,
            offsetY: 330,
            align: "center",
            style: {
                color: "#444",
            },
        },
        tooltip: {
            // Cập nhật tooltip để hiển thị VNĐ
            y: {
                formatter: function (val) {
                    return formatCurrencyVN(val * 1000000); // Áp dụng định dạng tiền tệ
                }
            }
        }
    };

    var chart = new ApexCharts(
        document.querySelector("#revenue-chart"),
        options_revenue_chart
    );
    chart.render();

    // =======================================================================
    // 3. XỬ LÝ MODAL (Đã có logic tiếng Việt, chỉ cần sắp xếp)
    // =======================================================================
    
    // Xử lý Modal Chỉnh sửa Nhân viên
    const editEmployeeModal = document.getElementById("editEmployeeModal");
    if (editEmployeeModal) {
        editEmployeeModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            const modalForm = this.querySelector("form");
            
            // Lấy và điền dữ liệu
            modalForm.querySelector("#editEmployeeId").value = button.getAttribute("data-id");
            modalForm.querySelector("#editEmployeeName").value = button.getAttribute("data-name");
            modalForm.querySelector("#editEmployeePosition").value = button.getAttribute("data-position");
            modalForm.querySelector("#editEmployeeEmail").value = button.getAttribute("data-email");
            modalForm.querySelector("#editEmployeeStatus").value = button.getAttribute("data-status");
            modalForm.querySelector("#editEmployeePhone").value = button.getAttribute("data-phone");
        });
    }

    // Hàm để xử lý khi nhấn nút "Cập nhật trạng thái" cho Nhân viên
    window.deactivateEmployee = function (button) {
        if (
            confirm(
                "Bạn có chắc chắn muốn cập nhật trạng thái của nhân viên này không?"
            )
        ) {
            const row = button.closest("tr");
            // const employeeId = row.getAttribute("data-id"); // Không dùng ở đây
            const statusBadge = row.querySelector(".status-badge");

            // Cập nhật trạng thái hiển thị trên giao diện
            statusBadge.classList.remove("bg-success");
            statusBadge.classList.add("bg-danger");
            statusBadge.textContent = "Không hoạt động";

            // TODO: Gửi yêu cầu AJAX lên server
        }
    };

    // Xử lý Modal Chỉnh sửa Người dùng
    const editUserModal = document.getElementById("editUserModal");
    if (editUserModal) {
        editUserModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            const modalForm = this.querySelector("form");

            // Lấy và điền dữ liệu
            modalForm.querySelector("#editUserId").value = button.getAttribute("data-id");
            modalForm.querySelector("#editUsername").value = button.getAttribute("data-username");
            modalForm.querySelector("#editEmail").value = button.getAttribute("data-email");
            modalForm.querySelector("#editUserRole").value = button.getAttribute("data-role");
            modalForm.querySelector("#editUserStatus").value = button.getAttribute("data-status");
        });
    }

    // Hàm để xử lý khi nhấn nút "Cập nhật trạng thái" cho Người dùng
    window.deactivateUser = function (button) {
        if (
            confirm(
                "Bạn có chắc chắn muốn cập nhật trạng thái của người dùng này không?"
            )
        ) {
            const row = button.closest("tr");
            // const userId = row.getAttribute("data-id"); // Không dùng ở đây
            const statusBadge = row.querySelector(".status-badge");

            // Cập nhật trạng thái hiển thị trên giao diện
            statusBadge.classList.remove("bg-success");
            statusBadge.classList.add("bg-danger");
            statusBadge.textContent = "Không hoạt động";

            // TODO: Gửi yêu cầu AJAX lên server
        }
    };

    // Xử lý Modal Chỉnh sửa Sản phẩm
    const editProductModal = document.getElementById("editProductModal");
    if (editProductModal) {
        editProductModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            const modalForm = this.querySelector("form");

            // Lấy và điền dữ liệu
            modalForm.querySelector("#editProductId").value = button.getAttribute("data-id");
            modalForm.querySelector("#editProductName").value = button.getAttribute("data-name");
            modalForm.querySelector("#editProductCategory").value = button.getAttribute("data-category");
            modalForm.querySelector("#editProductPrice").value = button.getAttribute("data-price");
            modalForm.querySelector("#editProductQuantity").value = button.getAttribute("data-quantity");
            modalForm.querySelector("#editProductStatus").value = button.getAttribute("data-status");
            modalForm.querySelector("#editProductDiscount").value = button.getAttribute("data-discount");
            
            // Hiển thị hình ảnh hiện tại
            const productImage = button.getAttribute("data-image");
            const previewImage = document.getElementById("editProductPreviewImage");
            if (productImage) {
                previewImage.src = productImage;
                previewImage.classList.remove("d-none");
            } else {
                previewImage.classList.add("d-none");
            }
        });
    }

    // Xử lý Modal Chỉnh sửa Voucher
    const editVoucherModal = document.getElementById("editVoucherModal");
    if (editVoucherModal) {
        editVoucherModal.addEventListener("show.bs.modal", function (event) {
            const button = event.relatedTarget;
            const modalForm = this.querySelector("form");

            // Lấy và điền dữ liệu
            modalForm.querySelector("#editVoucherId").value = button.getAttribute("data-id");
            modalForm.querySelector("#editVoucherCode").value = button.getAttribute("data-code");
            modalForm.querySelector("#editVoucherValue").value = button.getAttribute("data-value");
            modalForm.querySelector("#editVoucherQuantity").value = button.getAttribute("data-quantity");
            modalForm.querySelector("#editVoucherExpiry").value = button.getAttribute("data-expiry");
        });
    }

    // =======================================================================
    // 4. XỬ LÝ MODAL CHI TIẾT ĐƠN HÀNG (ORDER DETAIL MODAL)
    // =======================================================================

    const orderDetailModal = document.getElementById("orderDetailModal");

    if (orderDetailModal) {
        orderDetailModal.addEventListener("show.bs.modal", (event) => {
            const button = event.relatedTarget;

            // Lấy thông tin từ các thuộc tính data-*
            const orderId = button.getAttribute("data-order-id");
            const customerName = button.getAttribute("data-customer-name");
            const totalAmount = button.getAttribute("data-total-amount"); // Chuỗi đã được định dạng VNĐ từ HTML/PHP
            const shippingAddress = button.getAttribute("data-shipping-address");

            // Phân tích cú pháp chuỗi JSON từ data-products
            // Do giá trị price trong data-products có thể là số, ta cần định dạng nó
            let productsData = [];
            try {
                 productsData = JSON.parse(button.getAttribute("data-products"));
            } catch (e) {
                console.error("Lỗi parse JSON data-products:", e);
            }

            // Cập nhật nội dung modal
            document.getElementById("modal-order-id").textContent = orderId;
            document.getElementById("modal-customer-name").textContent = customerName;
            document.getElementById("modal-total-amount").textContent = totalAmount; 
            document.getElementById("modal-shipping-address").textContent = shippingAddress;

            // Hiển thị danh sách sản phẩm
            const productList = document.getElementById("modal-product-list");
            productList.innerHTML = ""; 

            productsData.forEach((product) => {
                const li = document.createElement("li");
                li.className =
                    "list-group-item d-flex justify-content-between align-items-center";
                
                // Kiểm tra và định dạng giá nếu giá trị product.price là số
                const formattedPrice = typeof product.price === 'number' 
                                     ? formatCurrencyVN(product.price) 
                                     : (product.price || '0 VNĐ'); // Giữ nguyên nếu đã là chuỗi hoặc mặc định

                li.innerHTML = `
                    <div>
                        <span>${product.name}</span>
                    </div>
                    <span class="text-muted">${
                        product.quantity
                    } x ${formattedPrice}</span>
                `;
                productList.appendChild(li);
            });
        });
    }
});