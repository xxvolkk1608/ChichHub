<?php

session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['Username'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['Username'];

// ดึงข้อมูล order ของผู้ใช้ปัจจุบัน
$stmt = $pdo->prepare("
    SELECT Orders.Ord_id, Orders.Date, Ord_detail.Amount
    FROM Member
    JOIN Orders ON Member.ID = Orders.ID
    JOIN Ord_detail ON Orders.Ord_id = Ord_detail.Ord_id
    WHERE Member.Username = ?
    ORDER BY Orders.Date DESC
    ");
$stmt->execute([$username]);
$Orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Order History</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
            margin-top: 5%;
        }
        .name1 {
            text-align: center;
        }
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
                <h1 class="chic-hub"><a href="../Home/home.php">ChicHub</a></h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="../Home/home.php">หน้าหลัก</a></li>
                    <li><a href="../Shop/shop.php">ร้านค้า</a></li>
                    <li><a href="../Category/Promotion.php">โปรโมชั่น</a></li>
                    <li><a href="../Contact-us/contact-us.php">ติดต่อเรา</a></li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-user"></i> สวัสดี, <?php echo htmlspecialchars($username); ?></a>
                        <div class="dropdown-content">
                            <a href="../User/edit_profile.php">แก้ไขข้อมูลส่วนตัว</a>
                            <a href="#" style="color: red;" onclick="confirmLogout()">ออกจากระบบ</a>
                        </div>
                    </li>
                    <li><a href="../Cart/cart.php"><i class="fas fa-shopping-cart"></i> รถเข็น</a></li>
                </ul>
                <div class="hamburger">
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header><br>

    <!-- Blur Background -->
    <div class="blur-background"></div>

    <!-- ส่วนแสดงประวัติการสั่งซื้อ -->
    <section class="contact-section">
        <h1 style="text-align: center;">ประวัติการสั่งซื้อของคุณ</h1>
        <?php
        if (!empty($Orders)) {
            $currentOrder = null;
            foreach ($Orders as $order) {
                // หากเป็นคำสั่งซื้อใหม่ให้แสดงหัวข้อคำสั่งซื้อ
                if ($currentOrder != $order['Ord_id']) {
                    if ($currentOrder !== null) {
                        echo "</table><br>";
                    }
                    $currentOrder = $order['Ord_id'];
                    echo "<h2>คำสั่งซื้อ #{$order['Ord_id']}</h2>";
                    echo "<p>วันที่สั่งซื้อ: {$order['Date']}</p>";
                    echo "<p>สถานะการชำระเงิน: {$order['Payment_status']}</p>";
                    echo "<table border='1'>
                            <tr>
                                <th>สินค้า</th>
                                <th>จำนวน</th>
                                <th>ราคา</th>
                            </tr>";
                }
                // แสดงรายละเอียดสินค้าในคำสั่งซื้อนี้
                echo "<tr>
                        <td>{$order['P_name']}</td>
                        <td>{$order['Amount']}</td>
                        <td>฿" . number_format($order['Price'], 2) . "</td>
                      </tr>";
            }
            echo "</table>";
        } else {
            echo "<p>ไม่มีประวัติการสั่งซื้อ</p>";
        }
        ?>
    </section>

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
                window.location.href = "./logout.php";
            }
        }
    </script>
</body>

</html>
