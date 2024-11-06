<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ล็อกอินหรือไม่
if (!isset($_SESSION['Username'])) {
    header('Location: signin.php');
    exit();
}

$username = $_SESSION['Username'];

// รับค่า Ord_id จาก URL
$ord_id = isset($_GET['Ord_id']) ? $_GET['Ord_id'] : null;

if ($ord_id) {
    // ดึงข้อมูล order ของผู้ใช้ปัจจุบันที่มี Ord_id ตรงกัน พร้อมที่อยู่จัดส่ง วิธีชำระเงิน และราคารวมหลังหักส่วนลด
    $stmt = $pdo->prepare("
        SELECT Orders.Ord_id, Orders.Date, Orders.shipping_address, Orders.payment_method, Orders.final_price, Ord_detail.Payment_status, Product.P_name, Ord_detail.Amount, Product.Price 
        FROM `Orders`
        INNER JOIN `Ord_detail` ON Orders.Ord_id = Ord_detail.Ord_id
        INNER JOIN `Product` ON Ord_detail.P_ID = Product.P_ID
        WHERE Orders.Ord_id = ?
    ");
    $stmt->execute([$ord_id]);
    $Orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($Orders)) {
        echo "ไม่พบข้อมูลคำสั่งซื้อ";
        exit();
    }
} else {
    header('Location: ../Home/home.php');
    exit();
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chic-hub - ร้านขายเสื้อผ้าออนไลน์</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="script.js"></script>
    <style>
        /* Style หลัก */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
        }
        .order-container {
            width: 90%;
            margin: 4rem auto;
            
        }
        .order-card:hover{
            transform: scale(1.05);
        }
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
            margin-top: 1.5rem;
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
        .finalp{
            text-align: end;
            font-size: large;
            font-weight: bold;
            margin-top: 1em;
        }
        .finalp1{
            text-align: end;
            font-size: large;
            font-weight: bold;
            color: #ff5722;
            margin-right: 0.5em;
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
                        <a href="#"><i class="fas fa-user"></i> สวัสดี,
                            <?php echo $username; ?>
                        </a>
                        <div class="dropdown-content">
                            <a href="../User/edit_profile.php">แก้ไขข้อมูลส่วนตัว</a>
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

    <!-- Blur Background -->
    <div class="blur-background"></div>

    <!-- ส่วนแสดงประวัติการสั่งซื้อ -->
    <section class="order-container">
        <h1 style="text-align: center; padding-top: 5vh;">ขอบคุณสำหรับการสั่งซื้อ</h1>
        <p style="text-align: center; padding-top: 3vh; padding-bottom: 5vh;">คำสั่งซื้อของคุณได้รับการชำระเรียบร้อยแล้ว!</p>
        <div class="order-card">
            <div class="order-header">คำสั่งซื้อ #<?php echo htmlspecialchars($ord_id); ?></div>
            <div class="order-details">
                <p>วันที่สั่งซื้อ: <?php echo htmlspecialchars($Orders[0]['Date']); ?></p>
                <p>วิธีการชำระเงิน: <?php echo htmlspecialchars($Orders[0]['payment_method']); ?></p>
                <p>สถานที่จัดส่ง: <?php echo htmlspecialchars($Orders[0]['shipping_address']); ?></p>
                <p>สถานะการชำระเงิน: <?php echo htmlspecialchars($Orders[0]['Payment_status']); ?></p>
            </div>
            <h3>รายละเอียดสินค้า</h3>
            <?php foreach ($Orders as $order): ?>
                <div class="product-item">
                    <span>สินค้า: <?php echo htmlspecialchars($order['P_name']); ?></span>
                    <span>จำนวน: <?php echo $order['Amount']; ?></span>
                    <span>ราคา: ฿<?php echo number_format($order['Price'], 2); ?></span>
                </div>
            <?php endforeach; ?>
            <p class="finalp">ราคารวมหลังหักส่วนลด: </p>
            <p class="finalp1">฿<?php echo number_format($Orders[0]['final_price'], 2); ?></p> <!-- แสดงราคารวมหลังหักส่วนลด -->
        </div>
        <a href="../Home/home.php" class="btn" style="display: block; text-align: center; margin-top: 20px;">กลับไปยังหน้าหลัก</a>
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
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productName = button.getAttribute('data-name');
                const productPrice = button.getAttribute('data-price');

                const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

                const existingItem = cartItems.find(item => item.name === productName);
                if (existingItem) {
                    existingItem.quantity += 1; // เพิ่มจำนวนสินค้า
                } else {
                    cartItems.push({
                        name: productName,
                        price: productPrice,
                        quantity: 1
                    });
                }

                localStorage.setItem('cartItems', JSON.stringify(cartItems));
                alert(`${productName} ถูกเพิ่มในรถเข็น`);
            });
        });

        function confirmLogout() {
            if (confirm("คุณต้องการออกจากระบบหรือไม่?")) {
                window.location.href = "../Home/logout.php";
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