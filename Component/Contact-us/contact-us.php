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
    <title>ChicHub - Contact Us</title>
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
                    <li><a href="./contact-us.html">ติดต่อเรา</a></li>
                    <li><a href="../Sign-In/signin.html"><i class="fas fa-user"></i> เข้าสู่ระบบ</a></li>
                    <li><a href="../Cart/cart.html"><i class="fas fa-shopping-cart"></i> รถเข็น</a></li>
                </ul>
                <!-- ปุ่ม Hamburger สำหรับมือถือ -->
                <div class="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header><br>

    <!-- Blur Background -->
    <div class="blur-background"></div>

    <!-- ส่วนติดต่อเรา -->
    <section class="contact-section">
        <div class="container">
            <h2>ติดต่อเรา</h2>
            <div class="contact-info">
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <p>ที่อยู่: 123 ถนนสายช้อปปิ้ง, กรุงเทพฯ 10100</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone-alt"></i>
                    <p>โทร: 02-123-4567</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <p>อีเมล: support@chichub.com</p>
                </div>
                <div class="info-item">
                    <i class="fas fa-clock"></i>
                    <p>เวลาทำการ: จันทร์-ศุกร์ 9:00 - 18:00 น.</p>
                </div>
            </div>

            <!-- ฟอร์มการติดต่อ -->
            <div class="contact-form">
                <h3>ส่งข้อความถึงเรา</h3>
                <form action="#">
                    <div class="input-group">
                        <label for="name">ชื่อของคุณ</label>
                        <input type="text" id="name" placeholder="กรอกชื่อของคุณ" required>
                    </div>
                    <div class="input-group">
                        <label for="email">อีเมลของคุณ</label>
                        <input type="email" id="email" placeholder="กรอกอีเมลของคุณ" required>
                    </div>
                    <div class="input-group">
                        <label for="message">ข้อความ</label>
                        <textarea id="message" rows="5" placeholder="พิมพ์ข้อความของคุณ" required></textarea>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn-submit">ส่งข้อความ</button>
                    </div>
                </form>
            </div>
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
