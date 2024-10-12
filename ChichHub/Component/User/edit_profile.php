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
    $newPassword = $_POST['password']; // รหัสผ่านใหม่

    // อัปเดตข้อมูลในตาราง Member_detail (ตาม MD_ID ที่เชื่อมกับ Username)
    if (!empty($newPassword)) {
        // ถ้ามีการกรอกรหัสผ่านใหม่ให้อัปเดตด้วย
        $stmt = $pdo->prepare("UPDATE Member_detail AS md 
                                JOIN Member AS m ON m.MD_ID = md.MD_ID
                                SET md.Email = ?, md.Address = ?, md.Tel = ?, m.Password = ?
                                WHERE m.Username = ?");
        $stmt->execute([$newEmail, $newAddress, $newPhone, $newPassword, $username]);
    } else {
        // ถ้าไม่มีการเปลี่ยนรหัสผ่าน
        $stmt = $pdo->prepare("UPDATE Member_detail AS md 
                                JOIN Member AS m ON m.MD_ID = md.MD_ID
                                SET md.Email = ?, md.Address = ?, md.Tel = ?
                                WHERE m.Username = ?");
        $stmt->execute([$newEmail, $newAddress, $newPhone, $username]);
    }

    echo "<script>
            alert('เปลี่ยนข้อมูลสำเร็จ');
            window.location.href = 'edit_profile.php';
          </script>";
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

    .container-userinfo {
        max-width: 900px;
        margin-top: 7rem;
        margin-left: auto;
        margin-bottom: 2rem;
        margin-right: auto;
        background-color: #fff;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group label {
        display: block;
        font-size: 16px;
        margin-bottom: 5px;
        color: #333;
    }

    .input-group input,
    .input-group textarea {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }

    .btn {
        background-color: #ff6347;
        color: #fff;
        padding: 12px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        border: none;
        margin-left: 39%;
    }

    .btn:hover {
        background-color: #ff4500;
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

    input[readonly] {
        background-color: #d3d3d3;
        color: #333;
        /* เปลี่ยน cursor ให้เป็นรูปห้ามคลิก */
        cursor: not-allowed;
    }

    @media (max-width: 600px) {
        .btn {
            margin-left: 4.5rem;
        }
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
                    <li><a href="../Shop/shop.php">ร้านค้า</a></li>
                    <li><a href="#">โปรโมชั่น</a></li>
                    <li><a href="../Contact-us/contact-us.php">ติดต่อเรา</a></li>
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

    <!-- เนื้อหาหลัก -->
    <div class="container-userinfo">
        <h2 style="text-align: center;">แก้ไขข้อมูลส่วนตัว</h2><br>
        <form action="edit_profile.php" method="POST">
            <div class="input-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($username); ?>"
                    readonly>
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
                <textarea name="address" id="address"
                    rows="4"><?php echo htmlspecialchars($userData['Address']); ?></textarea>
            </div>

            <!-- ฟิลด์สำหรับเปลี่ยนรหัสผ่าน -->
            <div class="input-group">
                <label for="password">รหัสผ่านใหม่ (หากไม่ต้องการเปลี่ยนให้เว้นว่างไว้)</label>
                <input type="password" name="password" id="password" placeholder="กรอกรหัสผ่านใหม่"
                    pattern="(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}"
                    title="รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวพิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข และอักขระพิเศษ">
                <span id="passwordError"
                    style="color: red; display: none;">กรุณากรอกรหัสผ่านให้ถูกต้องตามเงื่อนไข</span>
            </div>

            <button type="submit" class="btn">บันทึกการเปลี่ยนแปลง</button>
        </form>
    </div>

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
                window.location.href = "../Home/logout.php";
            }
        }
    </script>

</body>

</html>