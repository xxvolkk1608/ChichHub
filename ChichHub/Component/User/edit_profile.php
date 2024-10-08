<?php
// เริ่มต้นการใช้งาน session
session_start();

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION['Username'])) {
    header("Location: signin.php");
    exit();
}

$username = $_SESSION['Username'];

// เชื่อมต่อกับฐานข้อมูล
include 'connect.php';

// ตรวจสอบว่ามีการอัปเดตข้อมูลหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newEmail = $_POST['email'];
    $newAddress = $_POST['address'];
    $newPhone = $_POST['phone'];

    // อัปเดตข้อมูลในตาราง Member_detail (ตาม MD_ID ที่เชื่อมกับ Username)
    $stmt = $pdo->prepare("UPDATE Member_detail AS md 
                            JOIN Member AS m ON m.MD_ID = md.MD_ID
                            SET md.Email = ?, md.Address = ?, md.Tel = ?
                            WHERE m.Username = ?");
    $stmt->execute([$newEmail, $newAddress, $newPhone, $username]);

    // เปลี่ยนเส้นทางไปยังหน้า user_page.php
    header("Location: userpage.php");
    exit();
}

// ดึงข้อมูลปัจจุบันของผู้ใช้จากฐานข้อมูล
$stmt = $pdo->prepare("SELECT md.Email, md.Address, md.Tel, m.Username
                       FROM Member_detail AS md
                       JOIN Member AS m ON m.MD_ID = md.MD_ID
                       WHERE m.Username = ?");
$stmt->execute([$username]);
$userData = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขข้อมูลส่วนตัว</title>
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
                <li><a href="../Sign-In/user_page.php"><i class="fas fa-user"></i> สวัสดี, <?php echo $username; ?></a></li>
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
    <h2>แก้ไขข้อมูลส่วนตัว</h2><br>
    <form action="edit_profile.php" method="POST">
        <div class="input-group">
            <label for="username">ชื่อผู้ใช้</label>
            <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>" readonly>
        </div>

        <div class="input-group">
            <label for="email">อีเมล</label>
            <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($userData['Email']); ?>"
                   pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" required>
        </div>

        <div class="input-group">
            <label for="phone">เบอร์โทรศัพท์</label>
            <input type="text" name="phone" id="phone" value="<?php echo htmlspecialchars($userData['Tel']); ?>"
                   pattern="[0]{1}[0-9]{9}" maxlength="10" required>
        </div>

        <div class="input-group">
            <label for="address">ที่อยู่</label>
            <textarea name="address" id="address" rows="4"><?php echo htmlspecialchars($userData['Address']); ?></textarea>
        </div>

        <button type="submit" class="btn">บันทึกการเปลี่ยนแปลง</button>
    </form>
</div>

</body>
</html>
