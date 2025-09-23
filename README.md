# HugPog
HugPog là nền tảng thương mại điện tử mang đến trải nghiệm mua sắm trực tuyến mượt mà cho người dùng và công cụ quản lý hiệu quả cho quản trị viên. Hệ thống hỗ trợ đăng ký, đăng nhập, tìm kiếm, giỏ hàng, theo dõi đơn, đánh giá sản phẩm, quản lý khách hàng, sản phẩm, voucher, khuyến mãi và doanh thu.



# HugPog - Hệ thống Thương mại Điện tử

## 📌 Mô tả dự án
HugPog là một nền tảng thương mại điện tử được xây dựng để cung cấp trải nghiệm mua sắm trực tuyến mượt mà cho người dùng và công cụ quản lý hiệu quả cho quản trị viên.  

Website cho phép người dùng **đăng ký, đăng nhập, tìm kiếm sản phẩm, mua hàng, đánh giá sản phẩm, quản lý giỏ hàng, theo dõi đơn hàng, và nhận thông báo từ admin**.  

Quản trị viên có thể **quản lý sản phẩm, khách hàng, nhân viên, voucher, khuyến mãi, đơn hàng, và doanh thu**.  

Dự án sử dụng mô hình **Git Flow** với 4 nhánh chính: `main`, `develop`, `feature`, và `bugfix` để đảm bảo quy trình phát triển rõ ràng và dễ dàng mở rộng.  

---

## 🚀 Các tính năng chính

### 1. Người dùng (User)

- **Đăng ký, Đăng nhập, Quên mật khẩu**  
  - Đăng ký: Người dùng điền thông tin (tên tài khoản, mật khẩu, email) để tạo tài khoản.  
  - Đăng nhập: Nhập tên tài khoản, mật khẩu, chọn loại tài khoản (user/admin).  
  - Quên mật khẩu: Cung cấp email để nhận liên kết khôi phục mật khẩu qua Gmail.  

- **Tìm kiếm sản phẩm**  
  - Cơ bản: Nhập từ khóa (tên/mã sản phẩm) để hiển thị kết quả, sắp xếp theo độ liên quan hoặc giá.  
  - Nâng cao: Lọc sản phẩm theo loại, hãng, khoảng giá, đánh giá, tồn kho, kết hợp từ khóa.  

- **Bình luận và đánh giá sản phẩm**  
  - Chỉ người dùng đã mua sản phẩm được đánh giá (1-5 sao, bình luận, ảnh minh họa).  
  - Hệ thống kiểm duyệt đánh giá trước khi hiển thị công khai.  
  - Người dùng chưa mua sẽ nhận thông báo yêu cầu mua hàng.  

- **Mua hàng**  
  - Sản phẩm có sẵn: Chọn số lượng, thêm vào giỏ hàng hoặc mua ngay, hệ thống kiểm tra tồn kho.  
  - Sản phẩm hết hàng: Thông báo hết hàng, hỗ trợ đăng ký nhận thông báo qua email.  

- **Giỏ hàng, Thanh toán, Voucher**  
  - Xem và chỉnh sửa giỏ hàng (sản phẩm, số lượng, giá, tổng tiền).  
  - Áp dụng voucher: Nhập mã, hệ thống kiểm tra tính hợp lệ và giảm giá.  
  - Thanh toán: Chọn phương thức (chuyển khoản, thẻ, ví điện tử), điền thông tin giao hàng.  

- **Lịch sử mua hàng**  
  - Xem danh sách đơn hàng (mã, ngày, tổng tiền, trạng thái), lọc theo thời gian/trạng thái.  
  - Nếu chưa có đơn hàng, gợi ý quay lại trang chủ.  

- **Xem email từ admin**  
  - Xem email (xác nhận đơn, khuyến mãi) trong hộp thư tích hợp.  
  - Hỗ trợ đánh dấu đã đọc hoặc xóa email.  

- **Theo dõi giao hàng**  
  - Hiển thị trạng thái đơn hàng (chờ xác nhận, đang giao, đã giao) kèm thông tin vận chuyển.  
  - Đơn hàng hoàn tất/hủy hiển thị trạng thái cuối cùng.  

- **Hủy đơn hàng**  
  - Hủy nếu đơn chưa xác nhận/đang giao, yêu cầu lý do hủy.  
  - Đơn đã xác nhận/đang giao cần liên hệ admin.  

---

### 2. Quản trị viên (Admin)

- **Quản lý sản phẩm**  
  - Thêm: Điền thông tin sản phẩm (tên, mô tả, giá, hình ảnh, hãng, tồn kho, danh mục).  
  - Sửa: Cập nhật thông tin sản phẩm.  
  - Xóa: Xóa sản phẩm, lưu lịch sử xóa nếu cần.  

- **Quản lý nhân viên**  
  - Thêm: Tạo tài khoản nhân viên (tên, mật khẩu, email, quyền hạn).  
  - Xem: Xem danh sách nhân viên, lọc theo trạng thái.  
  - Khóa: Vô hiệu hóa tài khoản nhân viên.  

- **Quản lý đánh giá**  
  - Xem danh sách đánh giá, lọc theo sản phẩm/thời gian.  
  - Khóa đánh giá vi phạm (spam, nội dung không phù hợp).  

- **Quản lý khách hàng**  
  - Xem danh sách khách hàng (tên, email, cấp độ: diamond/gold/silver), lọc theo cấp độ.  
  - Khóa tài khoản khách hàng vi phạm.  
  - Cấp rank dựa trên điểm tích lũy hoặc tổng mua.  

- **Quản lý voucher**  
  - Thêm/sửa: Tạo hoặc cập nhật voucher (mã, giá trị, số lượng, thời hạn, điều kiện).  
  - Xem: Xem danh sách voucher, lọc theo trạng thái.  
  - Khóa: Vô hiệu hóa voucher hết hạn/không sử dụng.  

- **Quản lý khuyến mãi**  
  - Thêm: Tạo khuyến mãi (giảm giá %, thời gian, điều kiện).  
  - Xem: Xem danh sách khuyến mãi, lọc theo sản phẩm.  
  - Kết thúc: Loại bỏ khuyến mãi, trả về giá gốc.  

- **Quản lý đơn hàng**  
  - Xem danh sách đơn hàng, lọc theo trạng thái/thời gian.  
  - Cập nhật trạng thái (Chờ xác nhận → Xác nhận → Đang giao → Đã giao → Thành công), gửi thông báo.  
  - Hủy đơn hàng ở trạng thái Chờ xác nhận, hoàn tiền nếu cần.  

- **Quản lý doanh thu**  
  - Thống kê doanh thu (tuần, tháng, năm), hiển thị biểu đồ, xuất báo cáo.  
  - Xem sản phẩm bán chạy nhất và bán ế nhất, lọc theo thời gian/danh mục.  

---

## 🛠️ Công nghệ sử dụng
- **Frontend**: HTML, CSS, JavaScript  
- **Backend**: PHP  
- **Cơ sở dữ liệu**: MySQL (lưu trữ thông tin người dùng, sản phẩm, đơn hàng, voucher, v.v.)  
- **Tích hợp thanh toán**: (sẽ bổ sung theo nhu cầu)  

---

## 🌿 Cấu trúc nhánh (Git Flow)
Dự án sử dụng mô hình Git Flow với 4 nhánh chính:

- **main**  
  - Chứa mã ổn định, đã kiểm tra, sẵn sàng triển khai lên production.  
  - Chỉ merge từ nhánh `develop` sau khi vượt qua kiểm tra (unit tests, integration tests).  

- **develop**  
  - Nhánh tích hợp, nơi gộp mã từ nhánh `feature` và `bugfix`.  
  - Dùng để chạy kiểm tra tích hợp, đảm bảo ổn định trước khi merge vào `main`.  

- **feature**  
  - Phát triển các tính năng mới (ví dụ: đăng ký, tìm kiếm nâng cao, thanh toán).  
  - Tạo nhánh con (ví dụ: `feature/user-login`, `feature/advanced-search`) để phát triển riêng lẻ.  

- **bugfix**  
  - Sửa lỗi trong hệ thống.  
  - Tạo nhánh con (ví dụ: `bugfix/fix-payment-error`) để xử lý lỗi cụ thể, merge vào `develop` hoặc `main` nếu khẩn cấp.  
