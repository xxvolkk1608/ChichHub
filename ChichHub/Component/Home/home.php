<?php
session_start();

// ตรวจสอบว่ามี session อยู่หรือไม่
if (!isset($_SESSION["Username"])) {
    // ถ้าไม่มี session ให้เช็คว่ามี cookies หรือไม่
    if (isset($_COOKIE["Username"])) {
        // ตั้งค่า session ใหม่จาก cookies
        $_SESSION["Username"] = $_COOKIE["Username"];
    }
}

// ถ้าไม่มีทั้ง session และ cookies ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
if (!isset($_SESSION["Username"])) {
    header("Location: ../Sign-In/signin.php");
    exit();
}

// แสดงชื่อผู้ใช้
$username = htmlspecialchars($_SESSION["Username"]);
echo "สวัสดี, $username";
?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chic-hub - ร้านขายเสื้อผ้าออนไลน์</title>
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ลิงก์ไปยังไฟล์ CSS -->
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="script.js"></script>
    <style>
        header {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 80px;
            background-color: #fff;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            z-index: 999;
        }

        /* Dropdown Menu */
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            min-width: 160px;
            box-shadow: 0px 8px 16px 0px rgba(0, 0, 0, 0.2);
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 12px 16px;
            text-decoration: none;
            display: block;
        }

        .dropdown-content a:hover {
            background-color: #f1f1f1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }
    </style>
</head>

<body>

    <!-- ส่วนหัว (Header) -->
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="./home.php">ChicHub</a></h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="./home.php">หน้าหลัก</a></li>
                    <li><a href="../Shop/shop.php">ร้านค้า</a></li>
                    <li><a href="#">โปรโมชั่น</a></li>
                    <li><a href="../Contact-us/contact-us.html">ติดต่อเรา</a></li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-user"></i> สวัสดี, <?php echo $username; ?></a>
                        <div class="dropdown-content">
                            <a href="../User/edit_profile.php">แก้ไขข้อมูลส่วนตัว</a>
                            <a href="#" onclick="confirmLogout()">ออกจากระบบ</a>
                        </div>
                    </li>
                    <li><a href="../Cart/cart.php"><i class="fas fa-shopping-cart"></i> รถเข็น</a></li>
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

    <!-- ส่วนแบนเนอร์โปรโมชั่น -->
    <section class="banner">
        <div class="container">
            <img src="https://via.placeholder.com/1200x400" alt="โปรโมชั่น">
            <div class="banner-text">
                <h2>โปรโมชั่นลดราคาสูงสุด 50%</h2>
                <a href="#" class="btn">ช้อปเลย</a>
            </div>
        </div>
    </section>

    <!-- ส่วนแสดงสินค้า -->
    <section class="featured-products">
        <div class="container">
            <h2>สินค้ายอดนิยม</h2>
            <div class="products">
                <div class="product-card">
                    <img src="https://via.placeholder.com/300x400" alt="สินค้า 1">
                    <h3>เสื้อยืดคลาสสิก</h3>
                    <p>฿500</p>
                    <a href="#" class="btn">เพิ่มในรถเข็น</a>
                </div>
                <div class="product-card">
                    <img src="https://via.placeholder.com/300x400" alt="สินค้า 2">
                    <h3>กางเกงยีนส์</h3>
                    <p>฿800</p>
                    <a href="#" class="btn">เพิ่มในรถเข็น</a>
                </div>
                <div class="product-card">
                    <img src="https://via.placeholder.com/300x400" alt="สินค้า 3">
                    <h3>เสื้อแจ็คเก็ตสตรีท</h3>
                    <p>฿1200</p>
                    <a href="#" class="btn">เพิ่มในรถเข็น</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ส่วนลิงก์ไปยังหมวดหมู่สินค้า -->
    <section class="categories">
        <div class="container">
            <h2>หมวดหมู่สินค้า</h2>
            <div class="category-list">
                <div class="category-item">
                    <img class="tshirt" src="../../img/tshirt.png" alt="">
                    <a href="#">เสื้อยืด</a>
                </div>
                <div class="category-item">
                    <img class="shirt" src="../../img/shirt.png" alt="">
                    <a href="#">เสื้อเชิ้ต</a>
                </div>
                <div class="category-item">
                    <img class="pant" src="../../img/pant.png" alt="">
                    <a href="#">กางเกง</a>
                </div>
                <div class="category-item">
                    <img class="jacket" src="../../img/jacket.png" alt="">
                    <a href="#">เสื้อแจ็คเก็ต</a>
                </div>
                <!-- เพิ่มหมวดหมู่เพิ่มเติมได้ที่นี่ -->
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="#">เกี่ยวกับเรา</a>
                <a href="#">นโยบายความเป็นส่วนตัว</a>
                <a href="#">เงื่อนไขการใช้งาน</a>
                <a href="Contact-us/contact-us.html">ติดต่อเรา</a>
            </div>
            <div class="social-media">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
            <p>&copy; 2024 Chic-hub. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>

    <script>
        const hamburger = document.querySelector('.hamburger');
        const navLinks = document.querySelector('.nav-links');
        const blurBackground = document.querySelector('.blur-background');

        hamburger.addEventListener('click', () => {
            navLinks.classList.toggle('active');
            blurBackground.classList.toggle('active');
        });

        blurBackground.addEventListener('click', () => {
            navLinks.classList.remove('active');
            blurBackground.classList.remove('active');
        });

        function confirmLogout() {
            if (confirm("คุณต้องการออกจากระบบหรือไม่?")) {
                window.location.href = "./logout.php";
            }
        }
    </script>
</body>

</html>