<?php
function formatPrice($price)
{
    return number_format($price, 0, ',', '.') . ' VNĐ';
}
?>

<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <title>Trang chủ</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/style.css">
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>public/css/bootstrap.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick-theme.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.8.1/slick.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.css">
</head>

<style>
    .product-link {
        display: block;
        color: inherit;
        text-decoration: none;
    }

    .product-link:hover {
        color: inherit;
    }

    .slide-show {
        overflow: hidden;
        position: relative;
        max-width: 1120px;
        margin: auto;
    }

    .list-images {
        display: flex;
        transition: transform 0.5s ease;
    }

    .list-images img {
        width: 100%;
        flex-shrink: 0;
    }

    .slide-show .btn {
        font-size: 40px;
        color: #999;
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        transition: 0.3s;
        cursor: pointer;
    }

    .slide-show .btn:hover {
        color: white;
    }

    .btn-left {
        left: 10px;
    }

    .btn-right {
        right: 10px;
    }

    .index-images {
        position: absolute;
        bottom: 10px;
        display: flex;
        left: 50%;
        transform: translateX(-50%);
    }

    .index-item {
        border: 2px solid #999;
        padding: 4px;
        margin: 3px;
        border-radius: 50%;
        cursor: pointer;
    }

    .index-item.active {
        background-color: #999;
    }

    /* SECTION INSPIRATIONS - Sửa hoàn chỉnh */
    .section-inspirations {
        display: flex;
        justify-content: center;
        margin-top: 60px;
        margin-bottom: 60px;
        width: 100%;
    }

    .section-inspirations .bg-inspirations {
        max-width: 1440px;
        text-align: center;
    }

    .section-inspirations .ig-inspirations {
        display: flex;
        justify-content: center;
        gap: 20px;
        flex-wrap: wrap;
        transition: all 0.5s ease;
    }

    .section-inspirations .ig-inspirations img {
        border-radius: 30px;
        width: 340px;
        height: 360px;
        object-fit: cover;
        transition: all 0.6s ease;
        opacity: 0.5;
        cursor: pointer;
    }

    .section-inspirations .ig-inspirations img.active {
        opacity: 1;
        transform: scale(1.1);
    }

    .section-inspirations .ig-inspirations img:hover {
        opacity: 0.9;
        transform: scale(1.03);
    }

    /* Dots điều hướng */
    .section-inspirations .dots {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 15px;
        margin-top: 30px;
    }

    .section-inspirations .dot {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background-color: #bbb;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .section-inspirations .dot.active {
        width: 40px;
        border-radius: 10px;
        background-color: #333;
    }

    /* SECTION SHARE */
    .box-container {
        display: flex;
        max-width: 1340px;
        flex-direction: column;
        padding-right: var(--bs-gutter-x, 0.75rem);
        padding-left: var(--bs-gutter-x, 0.75rem);
        margin-right: auto;
        margin-left: auto;
    }



    .grid-container {
        display: grid;
        grid-template-columns: repeat(6, 1fr);
        /* Chia thành 6 cột */
        gap: 10px;
    }

    .grid-item img {
        width: 100%;
        height: auto;
        border-radius: 8px;
        object-fit: cover;
        cursor: pointer;
    }
</style>

<body>
    <?php include __DIR__ . '/../layout/header.php'; ?>

    <div class="section">
        <div class="slide-show">
            <div class="list-images">
                <img src="https://phukienahai.vn/media/banner/11_Mayf5319fda74f8541136d774303a8be444.jpg" alt="">
                <img src="https://phukiendexinh.com/image/1600/600/1/0/banners/thang%201.jpg" alt="">
                <img src="https://onewaymobile.vn/upload/1Banner/Artboard%202%20copy%2021@2x.png" alt="">
                <img src="https://vpem.vn/Uploads/2176/images/Banner/Vpem%20distribution%201920x763.jpg" alt="">
            </div>

            <div class="btn btn-left"><i class="fa-solid fa-arrow-left"></i></div>
            <div class="btn btn-right"><i class="fa-solid fa-arrow-right"></i></div>

            <div class="index-images">
                <div class="index-item index-item-0 active"></div>
                <div class="index-item index-item-1"></div>
                <div class="index-item index-item-2"></div>
                <div class="index-item index-item-3"></div>
            </div>
        </div>
    </div>


    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Sản phẩm gần đây</h3>
                    </div>
                </div>

                <?php if (!empty($productBlocks)): ?>
                    <?php foreach ($productBlocks as $products): ?>
                        <?php foreach ($products as $p):
                            $discountPercentage = $p['discount_percent'] ?? 0;
                            $salePrice = $p['discounted_price'] ?? $p['price'];
                        ?>
                            <div class="col-md-3 col-sm-6">
                                <div class="product">
                                    <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $p['id']; ?>" class="product-link">
                                        <div class="product-img">
                                            <img src="<?php echo htmlspecialchars($p['image_url'] ?? '../public/asset/image/no-image.png'); ?>" alt="<?php echo htmlspecialchars($p['name']); ?>">
                                            <?php if ($discountPercentage > 0): ?>
                                                <div class="product-label">
                                                    <span class="sale">-<?php echo $discountPercentage; ?>%</span>
                                                    <span class="new">SALE</span>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                        <div class="product-body">
                                            <p class="product-category"><?php echo htmlspecialchars($p['category_name'] ?? 'Chưa phân loại'); ?></p>
                                            <h3 class="product-name"><?php echo htmlspecialchars($p['name']); ?></h3>
                                            <h4 class="product-price">
                                                <?php echo formatPrice($salePrice); ?>
                                                <?php if ($discountPercentage > 0): ?>
                                                    <del class="product-old-price"><?php echo formatPrice($p['price']); ?></del>
                                                <?php endif; ?>
                                            </h4>
                                        </div>
                                    </a>
                                    <form class="add-to-cart" action="<?php echo BASE_URL; ?>public/add_to_cart.php" method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $p['id']; ?>">
                                        <button type="submit" class="add-to-cart-btn">
                                            <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                        </button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-12">
                        <p class="text-center">Không tìm thấy sản phẩm nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <?php
    // Thời gian kết thúc khuyến mãi
    $endTime = strtotime("2025-10-25 23:59:59");
    $timeLeft = max($endTime - time(), 0);
    ?>
    <div id="hot-deal" class="text-center py-5 bg-light">
        <ul id="countdown" class="list-inline" style="display: flex; justify-content: center; gap: 20px;">
            <li>
                <h3 id="days">00</h3><span>Ngày</span>
            </li>
            <li>
                <h3 id="hours">00</h3><span>Giờ</span>
            </li>
            <li>
                <h3 id="minutes">00</h3><span>Phút</span>
            </li>
            <li>
                <h3 id="seconds">00</h3><span>Giây</span>
            </li>
        </ul>
        <h2>Khuyến mãi hot tuần này</h2>
        <p>Giảm giá đến 10%</p>
        <a class="btn btn-danger" href="product_list.php">Mua ngay</a>
    </div>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Sản phẩm bán chạy</h3>
                    </div>
                </div>
                <?php if (!empty($top_products)): ?>
                    <?php foreach ($top_products as $tp): ?>
                        <div class="col-md-3 col-sm-6">
                            <div class="product">
                                <a href="<?php echo BASE_URL; ?>public/product.php?id=<?php echo $tp['id'] ?? 0; ?>" class="product-link">
                                    <div class="product-img">
                                        <img src="<?php echo htmlspecialchars($tp['image_url'] ?? '../public/asset/image/no-image.png'); ?>" alt="<?php echo htmlspecialchars($tp['product_name'] ?? 'Sản phẩm'); ?>" class="img-fluid" style="max-height: 250px; object-fit: cover; width: 100%;">
                                        <?php if (!empty($tp['discount_percent']) && $tp['discount_percent'] > 0): ?>
                                            <div class="product-label">
                                                <span class="sale">-<?php echo $tp['discount_percent']; ?>%</span>
                                                <span class="new">SALE</span>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="product-body">
                                        <p class="product-category"><?php echo htmlspecialchars($tp['category_name'] ?? 'Chưa phân loại'); ?></p>
                                        <h3 class="product-name"><?php echo htmlspecialchars($tp['product_name'] ?? $tp['name'] ?? 'Không rõ tên'); ?></h3>
                                        <h4 class="product-price">
                                            <?php echo formatPrice($tp['discounted_price'] ?? $tp['price'] ?? 0); ?>
                                            <?php if (!empty($tp['discount_percent']) && $tp['discount_percent'] > 0): ?>
                                                <del class="product-old-price"><?php echo formatPrice($tp['price']); ?></del>
                                            <?php endif; ?>
                                        </h4>
                                    </div>
                                </a>
                                <form class="add-to-cart" action="<?php echo BASE_URL; ?>public/add_to_cart.php" method="POST">
                                    <input type="hidden" name="product_id" value="<?php echo $tp['id'] ?? 0; ?>">
                                    <button type="submit" class="add-to-cart-btn">
                                        <i class="fa fa-shopping-cart"></i> Thêm vào giỏ
                                    </button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="col-md-12">
                        <p class="text-center">Không tìm thấy sản phẩm bán chạy nào.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="container">
        <div class="section-inspirations" id="section-inspirations">
        <div class="bg-inspirations">
            <div class="ig-inspirations">
                <img class="active" src="<?php echo BASE_URL; ?>public/asset/image/product01.png">
                <img src="<?php echo BASE_URL; ?>public/asset/image/product02.png">
                <img src="<?php echo BASE_URL; ?>public/asset/image/product03.png">
            </div>

            <div class="dots">
                <div class="dot active"></div>
                <div class="dot"></div>
                <div class="dot"></div>
            </div>
        </div>
    </div>
    </div>

    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="<?php echo BASE_URL; ?>public/asset/image/shop01.png" alt="" />
                        </div>
                        <div class="shop-body">
                            <h3>Laptop từ A->Z</h3>
                            <a href="https://thinkpro.vn/noi-dung/laptop-tu-a-z-huong-dan-su-dung-va-meo-toi-uu" class="cta-btn">Thông Tin <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="<?php echo BASE_URL; ?>public/asset/image/shop03.png" alt="" />
                        </div>
                        <div class="shop-body">
                            <h3>Phụ kiện Laptop cần có</h3>
                            <a href="https://maytinhgiaphat.vn/linh-kien-may-tinh-huong-dan-chi-tiet-tu-a-z/" class="cta-btn">Thông Tin <i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
                <div class="col-md-4 col-xs-6">
                    <div class="shop">
                        <div class="shop-img">
                            <img src="<?php echo BASE_URL; ?>public/asset/image/shop02.png" alt="" />
                        </div>
                        <div class="shop-body">
                            <h3>Tìm hiểu về Máy ảnh</h3>
                            <a href="https://viblo.asia/p/tim-hieu-ro-ve-may-anh-va-nhung-khai-niem-de-nham-lan-1VgZvp615Aw" class="cta-btn">Thông Tin<i class="fa fa-arrow-circle-right"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <section class="section-Share">
        <div class="container">
            <div class="section-title">
                <h3 class="title">Một số sản phẩm nổi bật</h3>
            </div>
            <div class="grid-container">
                <div class="grid-item "><img src="https://didongviet.vn/dchannel/wp-content/uploads/2022/09/havit-h2232d-tai-nghe-pc-didongviet@2x-2.jpg"></div>
                <div class="grid-item mt-5"><img src="https://sacduphongtot.vn/wp-content/uploads/2021/01/pin-sac-du-phong-cho-laptop-0901.jpg"></div>
                <div class="grid-item mt-4"><img src="https://edifiermiennam.vn/assets/loaedifier/Headphones/k815.jfif"></div>
                <div class="grid-item mt-3"><img src="https://cdn.vjshop.vn/may-anh/compact/fujifilm/fujifilm-x100vi/may-anh-fujifilm-x100vi-10-17.jpg"></div>
                <div class="grid-item mt-5"><img src="https://cdn.vjshop.vn/may-anh/instax/fujifilm-instax-mini-99/fujifilm-instax-mini-99-15.jpg"></div>
                <div class="grid-item"><img src="https://edifiermiennam.vn/assets/loaedifier/Headphones/G30123.png"></div>
                <div class="grid-item mt-5"><img src="https://static.chotot.com/storage/chotot-kinhnghiem/c2c/2023/01/ffbaf955-may-anh-gia-re-duoi-5-trieu-3.jpg"></div>
                <div class="grid-item"><img src="https://studiovietnam.com/wp-content/uploads/2022/10/may-anh-sony-03-1024x576.jpg"></div>
                <div class="grid-item mt-4"><img src="https://tiki.vn/blog/wp-content/uploads/2023/03/loa-nghe-nhac-hay.jpg"></div>
                <div class="grid-item"><img src="https://mega.com.vn/media/news/0510_Loa-Bluetooth-nghe-nhac.jpg"></div>
                <div class="grid-item mt-5"><img src="https://limosa.vn/wp-content/uploads/2023/05/cac-loai-chuot-may-tinh-1-768x400.jpg"></div>
                <div class="grid-item"><img src="https://tse1.mm.bing.net/th/id/OIP.W6Gpy9QUhD6eFoxhlV_iMQAAAA?pid=Api&P=0&h=220"></div>
            </div>
    </section>

    <?php include __DIR__ . '/../layout/footer.php'; ?>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.1/nouislider.min.js"></script>
    <script src="<?php echo BASE_URL; ?>public/js/main.js"></script>
    <script>
        let t = <?= $timeLeft ?>;

        function c() {
            if (t <= 0) return document.getElementById('countdown').innerHTML = "<h3>Hết khuyến mãi!</h3>";
            let d = Math.floor(t / 86400),
                h = Math.floor(t % 86400 / 3600),
                m = Math.floor(t % 3600 / 60),
                s = t % 60;
            days.textContent = d;
            hours.textContent = h;
            minutes.textContent = m;
            seconds.textContent = s;
            t--;
        }
        c();
        setInterval(c, 1000);
        const listImage = document.querySelector(".list-images");
        const imgs = document.querySelectorAll(".list-images img");
        const length = imgs.length;
        const btnRight = document.querySelector(".btn-right");
        const btnLeft = document.querySelector(".btn-left");
        const indexItems = document.querySelectorAll(".index-item");

        let current = 0;

        function updateSlide() {
            let width = imgs[0].offsetWidth;
            listImage.style.transform = `translateX(${-width * current}px)`;

            document.querySelector('.index-item.active').classList.remove('active');
            indexItems[current].classList.add('active');
        }

        function handleChangeSlide() {
            current = (current + 1) % length;
            updateSlide();
        }

        let handleEvenChangeSlide = setInterval(handleChangeSlide, 4000);

        btnRight.addEventListener("click", () => {
            clearInterval(handleEvenChangeSlide);
            handleChangeSlide();
            handleEvenChangeSlide = setInterval(handleChangeSlide, 4000);
        });

        btnLeft.addEventListener("click", () => {
            clearInterval(handleEvenChangeSlide);
            current = (current - 1 + length) % length;
            updateSlide();
            handleEvenChangeSlide = setInterval(handleChangeSlide, 4000);
        });

        // Click vào các chấm tròn để chuyển ảnh
        indexItems.forEach((item, index) => {
            item.addEventListener('click', () => {
                clearInterval(handleEvenChangeSlide);
                current = index;
                updateSlide();
                handleEvenChangeSlide = setInterval(handleChangeSlide, 4000);
            });
        });

        document.addEventListener("DOMContentLoaded", function() {
            const section = document.querySelector(".section-inspirations");
            const images = section.querySelectorAll(".ig-inspirations img");
            const dots = section.querySelectorAll(".dot");

            let current = 0;
            const total = images.length;

            function showSlide(index) {
                images.forEach((img) => {
                    img.classList.remove("active");
                    img.style.opacity = "0.5";
                });

                dots.forEach((dot) => {
                    dot.classList.remove("active");
                });

                images[index].classList.add("active");
                images[index].style.opacity = "1";
                dots[index].classList.add("active");
            }

            function nextSlide() {
                current = (current + 1) % total;
                showSlide(current);
            }

            // Auto chuyển
            let slideInterval = setInterval(nextSlide, 3000);
            showSlide(current);

            // Khi click dot
            dots.forEach((dot, index) => {
                dot.addEventListener("click", () => {
                    clearInterval(slideInterval);
                    current = index;
                    showSlide(current);
                    slideInterval = setInterval(nextSlide, 3000);
                });
            });

            // Khi click ảnh
            images.forEach((img, index) => {
                img.addEventListener("click", () => {
                    clearInterval(slideInterval);
                    current = index;
                    showSlide(current);
                    slideInterval = setInterval(nextSlide, 3000);
                });
            });
        });
    </script>
</body>

</html>