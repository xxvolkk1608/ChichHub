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
    <title>ChicHub - ร้านค้า</title>
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ลิงก์ไปยังไฟล์ CSS -->
    <!-- <link rel="stylesheet" href="../../styles.css"> -->
     <!--ลิงก์ของ XAMMP-->
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

    <div class="shop-container">
        <!-- ส่วนค้นหาสินค้า -->
        <div class="search-section">
          <input type="text" placeholder="ค้นหาสินค้า...">
          <button>ค้นหา</button>
        </div>
      
        <div class="shop-content">
          <!-- การกรองสินค้า -->
          <aside class="filter-sidebar">
            <h3>กรองสินค้า</h3>
            <div class="filter-category">
              <label>หมวดหมู่</label>
              <select>
                <option value="ทั้งหมด">ทั้งหมด</option>
                <option value="เสื้อยืด">เสื้อยืด</option>
                <option value="กางเกง">กางเกง</option>
              </select>
            </div>
            <div class="filter-color">
              <label>สี</label>
              <select>
                <option value="ทั้งหมด">ทั้งหมด</option>
                <option value="แดง">แดง</option>
                <option value="ดำ">ดำ</option>
              </select>
            </div>
            <div class="filter-size">
              <label>ขนาด</label>
              <select>
                <option value="select">-- size --</option>
                <option value="S">S</option>
                <option value="M">M</option>
                <option value="L">L</option>
                <option value="XL">XL</option>
              </select>
            </div>
            <div class="filter-price">
              <label>ราคา</label>
              <input type="number" min="0">
            </div>
          </aside>
      
          <!-- แสดงสินค้าทั้งหมด -->
          <section class="product-list">
            <div class="product-item">
              <img src="https://via.placeholder.com/300x400" alt="product">
              <h4>เสื้อยืดคลาสสิค</h4>
              <p>฿500</p>
            </div>
            <div class="product-item">
              <img src="https://via.placeholder.com/300x400" alt="product">
              <h4>กางเกงยีนส์</h4>
              <p>฿1200</p>
            </div>
            <div class="product-item">
              <img src="https://via.placeholder.com/300x400" alt="product">
              <h4>เสื้อแจ็คเก็ต</h4>
              <p>฿1500</p>
            </div>
          </section>
        </div>
      </div>
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