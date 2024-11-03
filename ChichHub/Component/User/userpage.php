<?php
// เริ่มต้นการใช้งาน session
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['Username'])) {
    // ถ้าไม่ได้เข้าสู่ระบบ เปลี่ยนเส้นทางไปที่หน้าเข้าสู่ระบบ
    header("Location: ../Sign-In/signin.php");
    exit();
}

// ดึงข้อมูลผู้ใช้จาก session
$username = $_SESSION['Username'];

// เชื่อมต่อกับฐานข้อมูล
include 'connect.php';

// ดึงข้อมูลผู้ใช้ปัจจุบันจากฐานข้อมูล (รวมทุกคอลัมน์ที่เกี่ยวข้องกับผู้ใช้)
$stmt = $pdo->prepare("SELECT * FROM Member m INNER JOIN Member_detail md ON m.MD_ID = md.MD_ID WHERE m.Username = ?");
$stmt->execute([$username]);
$userData = $stmt->fetch();

if (!$userData) {
    echo "ไม่พบข้อมูลผู้ใช้";
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ข้อมูลส่วนตัว</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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
                    <li><a href="../Shop/shop.html">ร้านค้า</a></li>
                    <li><a href="#">โปรโมชั่น</a></li>
                    <li><a href="../Contact-us/contact-us.html">ติดต่อเรา</a></li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-user"></i> สวัสดี, <?php echo $username; ?></a>
                        <div class="dropdown-content">
                            <a href="../User/edit_profile.php">แก้ไขข้อมูลส่วนตัว</a>
                            <a href="#" onclick="confirmLogout()">ออกจากระบบ</a>
                        </div>
                    </li>
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

<!-- เนื้อหาหลัก -->
<div class="container">
    <br><h2>ข้อมูลส่วนตัว</h2><br>
    <p><strong>ชื่อผู้ใช้:</strong> <?php echo htmlspecialchars($userData['Username']); ?></p>
    <p><strong>อีเมล:</strong> <?php echo htmlspecialchars($userData['Email']); ?></p>
    <p><strong>เบอร์โทรศัพท์:</strong> <?php echo htmlspecialchars($userData['Tel']); ?></p>
    <p><strong>ที่อยู่:</strong> <?php echo htmlspecialchars($userData['Address']); ?></p><br>
    
    <a href="edit_profile.php" class="btn">แก้ไขข้อมูลส่วนตัว</a>
</div>

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
