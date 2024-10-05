<?php
include '../db_connection.php';
session_start();
$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
    header('location:../Sign-In/signin.php');
};
if(isset($_GET['logout'])){
    unset($user_id);
    session_destroy();
    header('location:../Sign-In/signin.php');
}

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Shopping Cart</title>
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ลิงก์ไปยังไฟล์ CSS -->
    <link rel="stylesheet" href="../styles.css">
</head>
<body>
    <!-- ส่วนหัว (Header) -->
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="../Home/home.html">ChicHub</a></h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="../Home/home.html">หน้าหลัก</a></li>
                    <li><a href="../Shop/shop.html">ร้านค้า</a></li>
                    <li><a href="#">โปรโมชั่น</a></li>
                    <li><a href="../Contact-us/contact-us.html">ติดต่อเรา</a></li>
                    <li><a href="../Sign-In/signin.html"><i class="fas fa-user"></i> เข้าสู่ระบบ</a></li>
                    <li><a href="../Cart/cart.html"><i class="fas fa-shopping-cart"></i> รถเข็น</a></li>
                </ul>
                <!-- ปุ่ม Hamburger สำหรับมือถือ -->
                <div class="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header>

    <!-- Blur Background -->
    <div class="blur-background"></div>

    <!-- ส่วนของระบบรถเข็น -->
    <section class="cart-section">
        <div class="container">
            <h2>ตะกร้าสินค้าของคุณ</h2>
            <div class="cart-items">
                <!-- สินค้าในรถเข็น -->
                <div class="cart-item">
                    <img src="https://via.placeholder.com/100x150" alt="สินค้า 1">
                    <div class="item-details">
                        <h3>เสื้อยืดคลาสสิก</h3>
                        <p>฿500</p>
                        <label for="quantity1">จำนวน:</label>
                        <input type="number" id="quantity1" value="1" min="1">
                    </div>
                    <div class="remove-item">
                        <button class="remove-btn">ลบ</button>
                    </div>
                </div>
                <div class="cart-item">
                    <img src="https://via.placeholder.com/100x150" alt="สินค้า 2">
                    <div class="item-details">
                        <h3>กางเกงยีนส์</h3>
                        <p>฿800</p>
                        <label for="quantity2">จำนวน:</label>
                        <input type="number" id="quantity2" value="1" min="1">
                    </div>
                    <div class="remove-item">
                        <button class="remove-btn">ลบ</button>
                    </div>
                </div>
            </div>

            <!-- ส่วนสรุปราคาสินค้า -->
            <div class="cart-summary">
                <h3>สรุปการสั่งซื้อ</h3>
                <p>ราคารวม: <span class="total-price">฿1300</span></p>
                <button class="checkout-btn">ชำระเงิน</button>
            </div>
        </div>
    </section>

    <!-- ส่วนของการติดตามสถานะการจัดส่ง -->
    <section class="tracking-section">
        <div class="container">
            <h2>ติดตามสถานะการจัดส่ง</h2>
            <form action="#">
                <div class="input-group">
                    <label for="tracking-number">กรอกหมายเลขติดตาม</label>
                    <input type="text" id="tracking-number" placeholder="กรอกหมายเลขติดตาม">
                </div>
                <div class="input-group">
                    <button type="submit" class="tracking-btn">ติดตาม</button>
                </div>
            </form>
        </div>
    </section>

    <!-- ฟุตเตอร์ (Footer) -->
    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="#">เกี่ยวกับเรา</a>
                <a href="#">นโยบายความเป็นส่วนตัว</a>
                <a href="#">เงื่อนไขการใช้งาน</a>
                <a href="#">ติดต่อเรา</a>
            </div>
            <div class="social-media">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
            <p>&copy; 2024 ChicHub. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>

    <script>
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');
        const blurBackground = document.querySelector('.blur-background');
      
        hamburger.addEventListener('click', () => {
          navLinks.classList.toggle('active');
          blurBackground.classList.toggle('active'); // เบลอพื้นหลังเมื่อเมนูเปิด
        });
      
        // ปิดเมนูเมื่อคลิกที่เบลอพื้นหลัง
        blurBackground.addEventListener('click', () => {
          navLinks.classList.remove('active');
          blurBackground.classList.remove('active');
        });
      </script>
</body>
</html>
