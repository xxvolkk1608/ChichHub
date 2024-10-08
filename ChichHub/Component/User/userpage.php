<?php
// เริ่มต้นการใช้งาน session
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['Username'])) {
    // ถ้าไม่ได้เข้าสู่ระบบ เปลี่ยนเส้นทางไปที่หน้าเข้าสู่ระบบ
    header("Location: signin.php");
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
                <li><a href="../Contact-us/contact-us.php">ติดต่อเรา</a></li>
                <li><a href="../Sign-In/user_page.php"><i class="fas fa-user"></i> สวัสดี, <?php echo htmlspecialchars($username); ?></a></li>
                <li><a href="../Sign-In/logout.php"><i class="fas fa-sign-out-alt"></i> ออกจากระบบ</a></li>
                <li><a href="../Cart/cart.php"><i class="fas fa-shopping-cart"></i> รถเข็น</a></li>
            </ul>
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

</body>
</html>
