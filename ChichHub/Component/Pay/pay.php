<?php


session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามี session ผู้ใช้อยู่หรือไม่
if (!isset($_SESSION["Username"])) {
    die("กรุณาเข้าสู่ระบบ");
}

// รับ Ord_detail_id จาก URL
$Ord_id = $_GET['Ord_id'] ?? null;

if ($Ord_id) {
    // ดึงข้อมูลคำสั่งซื้อจากฐานข้อมูล ตรวจสอบให้แน่ใจว่าใช้ชื่อคอลัมน์ที่ถูกต้องในคำสั่ง SQL
    $stmt = $pdo->prepare("SELECT * FROM Ord_detail WHERE Ord_id = ?");
    $stmt->execute([$Ord_id]);
    $Ord_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($Ord_detail) {
        //echo "<h2>รายการสั่งซื้อของคุณ</h2>";
        foreach ($Ord_detail as $Ord_detail) {
            //echo "<p>สินค้า: {$Ord_detail['P_ID']} - จำนวน: {$Ord_detail['Amount']} - สถานะการชำระเงิน: {$Ord_detail['Payment_status']}</p>";
        }
    } else {
        echo "<p>ไม่พบคำสั่งซื้อ</p>";
    }
} else {
    echo "<p>ไม่พบคำสั่งซื้อ</p>";
}

// แสดงชื่อผู้ใช้
$username = htmlspecialchars($_SESSION["Username"]);

?>





<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Payment</title>
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

        body {
            font-family: Arial, sans-serif;
        }

        .payment-container {
            max-width: 600px;
            margin: 10% 30%;
            padding: 20px;
            bOrd_detail: 1px solid #ddd;
            bOrd_detail-radius: 8px;
            background-color: #f9f9f9;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        h2 {
            text-align: center;
        }

        .payment-method {
            margin: 20px 0;
        }

        .payment-method label {
            margin-right: 15px;
        }

        .payment-method input {
            margin-right: 10px;
        }

        .pay-button {
            width: 100%;
            padding: 10px;
            background-color: #ff5722;
            bOrd_detail: none;
            color: white;
            font-size: 18px;
            cursor: pointer;
            bOrd_detail-radius: 5px;
            text-align: center;
        }

        .pay-button:hover {
            background-color: #e64a19;
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
                        <a href="#"><i class="fas fa-user"></i> สวัสดี, <?php echo $username; ?></a>
                        <div class="dropdown-content">
                            <a href="../User/edit_profile.php">แก้ไขข้อมูลส่วนตัว</a>
                            <!-- ประวัติการสั่งซื้อ -->
                            <a href="../Order/order_history.php">ประวัติการสั่งซื้อ</a>
                            <?php if ($user['Role'] == 1): ?> <!-- เฉพาะ Admin ที่มี Role = 1 -->
                                <a href="../Admin/add-product.php">เพิ่มสินค้า</a>
                            <?php endif; ?>
                            <a href="#" style="color: red;" onclick="confirmLogout()">ออกจากระบบ</a>
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

    <div class="payment-container">
        <h2>เลือกวิธีการชำระเงิน</h2>

        <form method="POST" action="process_payment.php">
            <input type="hidden" name="Ord_id" value="<?php echo $Ord_id; ?>">
            <label for="payment_method">เลือกวิธีการชำระเงิน:</label>
            <select name="payment_method">
                <option value="cash">เงินสด</option>
                <option value="credit_card">บัตรเครดิต</option>
                <option value="mobile_banking">Mobile Banking</option>
            </select>
            <button type="submit">ยืนยันการชำระเงิน</button>
        </form>

    </div>

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
        const paymentForm = document.getElementById('payment-form');

        // ฟังก์ชันจัดการการส่งฟอร์ม
        paymentForm.addEventListener('submit', function (e) {
            e.preventDefault();

            // รับค่าการเลือกวิธีการชำระเงิน
            const paymentMethod = document.querySelector('input[name="payment-method"]:checked').value;

            // ส่งข้อมูลไปยัง process_payment.php
            checkoutButton.addEventListener('click', () => {
                if (cartItems.length === 0) {
                    alert("ตะกร้าของคุณว่างเปล่า");
                    return;
                }

                // ส่งข้อมูลตะกร้าสินค้าไปยังเซิร์ฟเวอร์เพื่อประมวลผลชำระเงิน
                fetch('process_payment.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ method: document.querySelector('input[name="payment-method"]:checked').value }), // ส่งข้อมูลวิธีการชำระเงิน
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert('การชำระเงินสำเร็จ');
                            localStorage.removeItem('cartItems'); // ล้างตะกร้าหลังจากการชำระเงินสำเร็จเท่านั้น
                            window.location.href = 'thankyou.php'; // ไปยังหน้าขอบคุณ
                        } else {
                            alert('เกิดข้อผิดพลาดในการชำระเงิน');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('เกิดข้อผิดพลาด');
                    });
            });
        });

    </script>

</body>

</html>