<?php
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION["Username"])) {
    // หากยังไม่ได้เข้าสู่ระบบ เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
    header("Location: ../Sign-In/signin.php");
    exit();
}

// ดึงชื่อผู้ใช้จาก session
$username = htmlspecialchars($_SESSION["Username"]);
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
    <link rel="stylesheet" href="../styles/styles.css">
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


        .contact-section {
            margin-top: 7rem;
            min-height: 71rem;
        }

        .name1 {
            text-align: center;
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


        @media (max-width: 600px) {
            .contact-info {
                display: block;
            }
        }
    </style>
</head>

<body>
    <!-- ส่วนหัว (Header) -->
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="../Home/home.php">ChicHub</a></h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="../Home/home.php">หน้าหลัก</a></li>
                    <li><a href="../Shop/shop.php">ร้านค้า</a></li>
                    <li><a href="#">โปรโมชั่น</a></li>
                    <li><a href="./contact-us.php">ติดต่อเรา</a></li>
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
            <div>
                <h3 class="name1">
                    By Web dev. Sec.1<br>
                    64-040626-6307-0 นายมิ่งณวัส กิตติมาสถาพร<br>
                    64-040626-6310-0 นายกิตติธัช พูนนายม<br>
                    64-040626-6311-8 นางสาวจารุศิริ กอบแก้ว<br>
                    64-040626-6313-4 นายณัฐภัทร เงินชูศรี<br>
                </h3>
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
            blurBackground.classList.toggle('active');
        });

        blurBackground.addEventListener('click', () => {
            navLinks.classList.remove('active');
            blurBackground.classList.remove('active');
        });

        function confirmLogout() {
            if (confirm("คุณต้องการออกจากระบบหรือไม่?")) {
                window.location.href = "../Home/logout.php";
            }
        }
    </script>
</body>

</html>