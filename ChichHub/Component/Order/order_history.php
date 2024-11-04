<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['Username'])) {
    header('Location: signin.php');
    exit();
}

// ตรวจสอบว่ามีการตั้งค่าคุกกี้ user_login หรือไม่
if (!isset($_COOKIE['user_login'])) {
    // หากไม่มีคุกกี้หรือตรวจพบว่าหมดอายุ
    session_unset(); // ล้าง session
    session_destroy(); // ทำลาย session
    setcookie("user_login", "", time() - 1800, "/"); // ลบคุกกี้
    
    // เปลี่ยนเส้นทางไปยังหน้าล็อกอิน
    header("Location: ../Sign-In/signin.php");
    exit();
}
$username = $_SESSION['Username'];

// ดึงข้อมูล order ของผู้ใช้ปัจจุบัน โดยใช้ shipping_address จาก Orders
$stmt = $pdo->prepare("
    SELECT Orders.Ord_id, Orders.Date, Orders.shipping_address, Ord_detail.Payment_status, Product.P_name, Ord_detail.Amount, Product.Price
    FROM `Orders`
    INNER JOIN `Ord_detail` ON Orders.Ord_id = Ord_detail.Ord_id
    INNER JOIN `Product` ON Ord_detail.P_ID = Product.P_ID
    INNER JOIN `Member` ON Orders.ID = Member.ID
    WHERE Member.Username = ?
    ORDER BY Orders.Ord_id DESC
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
        /* Style หลัก */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .order-container {
            width: 90%;
            margin: 2rem auto;
        }
<<<<<<< HEAD
=======
        .order-card:hover {
            transform: scale(1.05);
        }
>>>>>>> 58a66f63b771ddeb104ab13f81ef115ac2338ea5
        .order-card {
            border: 1px solid #ddd;
            border-radius: 8px;
            padding: 16px;
            margin-bottom: 1.5rem;
            background-color: #f9f9f9;
        }
        .order-header {
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }
        .order-details {
            margin-left: 1rem;
            color: #555;
        }
        .product-item {
            display: flex;
            justify-content: space-between;
<<<<<<< HEAD
            margin-top: 0.5rem;
=======
            margin-top: 1.5rem;
>>>>>>> 58a66f63b771ddeb104ab13f81ef115ac2338ea5
        }
        .product-item span {
            font-size: 0.9rem;
            color: #444;
        }

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
<<<<<<< HEAD
                 <!-- ปุ่ม Hamburger สำหรับมือถือ -->
                 <div class="hamburger">
=======
                <!-- ปุ่ม Hamburger สำหรับมือถือ -->
                <div class="hamburger">
>>>>>>> 58a66f63b771ddeb104ab13f81ef115ac2338ea5
                    <i class="fas fa-bars"></i>
                </div>
            </nav>
        </div>
    </header><br><br>
<<<<<<< HEAD
    
=======

>>>>>>> 58a66f63b771ddeb104ab13f81ef115ac2338ea5
    <!-- Blur Background -->
    <div class="blur-background"></div>
    

    <!-- ส่วนแสดงประวัติการสั่งซื้อ -->
    <section class="order-container">
        <h1 style="text-align: center; padding-top: 2vh; margin-bottom: 2vh">ประวัติการสั่งซื้อ</h1>
        <?php
        if (!empty($Orders)) {
            $currentOrder = null;
            foreach ($Orders as $order) {
                if ($currentOrder != $order['Ord_id']) {
                    if ($currentOrder !== null) {
                        echo "</div>"; // ปิดกล่องคำสั่งซื้อก่อนหน้า
                    }
                    $currentOrder = $order['Ord_id'];
                    echo "<div class='order-card'>";
                    echo "<div class='order-header'>คำสั่งซื้อ #{$order['Ord_id']}</div>";
                    echo "<div class='order-details'>";
                    echo "<p>วันที่สั่งซื้อ: {$order['Date']}</p>";
                    echo "<p>สถานที่จัดส่ง: {$order['shipping_address']}</p>";
                    echo "<p>สถานะการชำระเงิน: {$order['Payment_status']}</p>";
                    echo "</div>";
                }
                echo "<div class='product-item'>";
<<<<<<< HEAD
                echo "<span>สินค้า: {$order['P_name']}</span>";
=======
                echo "<span>สินค้า: {$order['P_name']}  </span>";
>>>>>>> 58a66f63b771ddeb104ab13f81ef115ac2338ea5
                echo "<span>จำนวน: {$order['Amount']}</span>";
                echo "<span>ราคา: ฿" . number_format($order['Price'], 2) . "</span>";
                echo "</div>";
            }
            echo "</div>"; // ปิดกล่องคำสั่งซื้อสุดท้าย
        } else {
            echo "<p style='text-align: center;'>ไม่มีประวัติการสั่งซื้อ</p>";
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
        function confirmLogout() {
            if (confirm("คุณต้องการออกจากระบบหรือไม่?")) {
                window.location.href = "./logout.php";
            }
        }

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
    </script>
</body>
</html>




