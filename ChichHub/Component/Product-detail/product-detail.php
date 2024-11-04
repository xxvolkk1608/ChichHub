<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือไม่
if (!isset($_SESSION["Username"])) {
    header("Location: ../Sign-In/signin.php");
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

// รับค่า id ของสินค้า
$product_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// ตรวจสอบว่ามีการส่งค่า id มา
if ($product_id <= 0) {
    echo "รหัสสินค้าที่ส่งมาไม่ถูกต้อง";
    exit();
}
$username = htmlspecialchars($_SESSION["Username"]);

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$stmt = $pdo->prepare("SELECT Product.P_ID, Product.P_name, Product.Price, Product.Color, Product.Detail, Images.IMG_path 
                       FROM Product 
                       INNER JOIN Images ON Product.IMG_ID = Images.IMG_ID
                       WHERE Product.P_ID = ?");
$stmt->execute([$product_id]);
$product = $stmt->fetch();

// ถ้าไม่มีสินค้าที่ตรงกับ id ให้กลับไปที่หน้า shop.php
if (!$product) {
    header("Location: ../Shop/shop.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายละเอียดสินค้า - ChicHub</title>
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

        .cart-section {
            background-color: var(--light-color);
            padding: 7rem 0 3rem 0;
            min-height: 78rem;
        }
        /* สไตล์ส่วนรายละเอียดสินค้า */
    .product-detail {
        display: flex;
        align-items: center;
        padding: 5rem 0;
        margin-top: 90px;
        max-width: 1200px;
        margin: 0 auto;
    }

    .product-image img {
        max-width: 500px;
        width: 100%;
        border-radius: 8px;
        box-shadow: 0px 4px 20px rgba(0, 0, 0, 0.1);
    }

    .product-info {
        margin-left: 40px;
        max-width: 600px;
    }

    .product-info h2 {
        font-size: 2.5rem;
        margin-bottom: 15px;
        color: #343a40;
    }

    .product-info p {
        font-size: 1.3rem;
        margin-bottom: 15px;
        color: #666;
    }

        .add-to-cart {
            padding: 10px 20px;
            background-color: #ff6f61;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .add-to-cart:hover {
            background-color: #e65a4f;
        }
        /* ปรับให้เข้ากับหน้าจอ iPhone */
    @media (max-width: 768px) {
    
        .product-detail {
            flex-direction: column;
            text-align: center;
            padding: 1rem;
        }

        .product-image img {
            max-width: 300px;
            margin-top: 10em;
        }

        .product-info {
            margin-left: 0;
            padding-top: 20px;
        }

        .product-info h2 {
            font-size: 2rem;
        }

        .product-info p {
            font-size: 1rem;
        }

        .add-to-cart {
            width: 100%;
            padding: 10px;
            font-size: 1.2rem;
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


    <!-- Blur Background -->
    <div class="blur-background"></div>

    <!-- แสดงรายละเอียดสินค้า -->
    <section class="product-detail">
        <div class="product-image">
            <img src="<?php echo $product['IMG_path']; ?>" alt="<?php echo htmlspecialchars($product['P_name']); ?>">
        </div>
        <div class="product-info">
            <h2><?php echo htmlspecialchars($product['P_name']); ?></h2>
            <p>ราคา: ฿<?php echo number_format($product['Price'], 2); ?></p>
            <p>สี: <?php echo htmlspecialchars($product['Color']); ?></p>
            <p>รายละเอียดสินค้า: <?php echo htmlspecialchars($product['Detail']); ?></p>

            <!-- ปุ่มเพิ่มในรถเข็น -->
            <button class="add-to-cart" data-id="<?php echo $product['P_ID']; ?>" data-name="<?php echo htmlspecialchars($product['P_name']); ?>" data-price="<?php echo $product['Price']; ?>">
                เพิ่มในรถเข็น
            </button>
        </div>
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
                window.location.href = "../Home/logout.php";
            }
        }

        // การเพิ่มสินค้าลงในรถเข็น
        const addToCartButton = document.querySelector('.add-to-cart');
        addToCartButton.addEventListener('click', () => {
            const productId = addToCartButton.dataset.id;
            const productName = addToCartButton.dataset.name;
            const productPrice = addToCartButton.dataset.price;

            const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

            // ตรวจสอบว่ามีสินค้าในรถเข็นแล้วหรือยัง
            const existingItem = cartItems.find(item => item.id === productId);
            if (existingItem) {
                existingItem.quantity += 1; // เพิ่มจำนวนสินค้า
            } else {
                cartItems.push({
                    id: productId,
                    name: productName,
                    price: productPrice,
                    quantity: 1
                });
            }

            // บันทึกข้อมูลสินค้าใน localStorage
            localStorage.setItem('cartItems', JSON.stringify(cartItems));
            alert(`${productName} ถูกเพิ่มในรถเข็น`);
        });
    </script>
</body>
</html>
