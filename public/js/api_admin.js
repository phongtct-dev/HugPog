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
    return new Intl.NumberFormat("vi-VN", {
      style: "currency",
      currency: "VND",
      minimumFractionDigits: 0, // Không hiển thị số lẻ
      maximumFractionDigits: 0,
    })
      .format(amount)
      .replace("₫", " VNĐ"); // Thay ký hiệu ₫ bằng VNĐ (tùy chọn)
  };

  // 2. BIỂU ĐỒ DOANH THU (REVENUE CHART) - DÙNG DỮ LIỆU GIẢ LẬP
  // =======================================================================

  // Kiểm tra xem biến dữ liệu đã được định nghĩa và thư viện ApexCharts đã tải
  if (
    typeof REVENUE_CHART_DATA !== "undefined" &&
    typeof ApexCharts !== "undefined"
  ) {
    const chartData = REVENUE_CHART_DATA;
    const chartElement = document.getElementById("chart-profile-visits");

    if (chartElement && chartData.dates.length > 0) {
      // Định dạng ngày hiển thị (dạng dd/mm)
      const formatDates = chartData.dates.map((dateStr) => {
        const date = new Date(dateStr);
        return date.getDate() + "/" + (date.getMonth() + 1);
      });

      var options_revenue_chart = {
        series: [
          {
            name: "Doanh thu",
            data: chartData.revenues, 
          },
        ],
        chart: {
          height: 350,
          type: "line",
          toolbar: {
            show: false,
          },
        },
        dataLabels: { enabled: false },
        stroke: {
          curve: "smooth",
          width: 3, 
          colors: ["#435ebe"], // Màu xanh tím (tùy chọn)
        },
        grid: {
          borderColor: "#e7e7e7",
        },
        xaxis: {
          categories: formatDates, // Ngày trên trục X
          title: {
            text: "Ngày trong 30 ngày",
          },
          labels: {
            rotate: -45,
            // Hiển thị tất cả các ngày, không ẩn ngày xen kẽ
            formatter: function (val, timestamp, opts) {
              return val;
            },
          },
        },
        yaxis: {
          title: {
            text: "Doanh thu (VNĐ)",
          },
          labels: {
            // Giả định hàm formatCurrencyVN đã có trong api_admin.js
            formatter: function (value) {
              return formatCurrencyVN(value);
            },
          },
        },
        tooltip: {
          y: {
            formatter: function (val) {
              return formatCurrencyVN(val);
            },
          },
        },
      };

      // Khởi tạo và hiển thị biểu đồ
      var chart = new ApexCharts(chartElement, options_revenue_chart);
      chart.render();
    }
  }

 
});
