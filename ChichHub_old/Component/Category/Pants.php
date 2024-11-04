<?php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connect.php'; // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION["Username"])) {
    header("Location: ../Sign-In/signin.php");
    exit();
}

$username = htmlspecialchars($_SESSION["Username"]);

// กำหนดหมวดหมู่สินค้าที่ต้องการกรองเป็น 'Pants' หรือ C_ID ของกางเกงจากฐานข้อมูล
$pants_category_id = 1002; // กำหนด C_ID ของหมวดหมู่กางเกง

// ตรวจสอบว่ามีการส่ง POST มาจริงหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $min_price = isset($_POST['min_price']) && $_POST['min_price'] != '' ? (int) $_POST['min_price'] : 0;
    $max_price = isset($_POST['max_price']) && $_POST['max_price'] != '' ? (int) $_POST['max_price'] : 0;
    $color = isset($_POST['color']) && $_POST['color'] != '' ? strtolower($_POST['color']) : ''; // เปลี่ยนให้เป็นตัวพิมพ์เล็กทั้งหมด

    // เก็บค่าการกรองใน session
    $_SESSION['min_price_filter'] = $min_price;
    $_SESSION['max_price_filter'] = $max_price;
    $_SESSION['color_filter'] = $color;

    // รีไดเรกต์ไปที่หน้า Pants.php (เพื่อแก้ปัญหาการส่งฟอร์มซ้ำ)
    header("Location: Pants.php");
    exit();
}

// นำค่าจาก session มาใช้ (ถ้ามี)
$min_price = isset($_SESSION['min_price_filter']) ? (int) $_SESSION['min_price_filter'] : 0;
$max_price = isset($_SESSION['max_price_filter']) ? (int) $_SESSION['max_price_filter'] : 0;
$color = isset($_SESSION['color_filter']) ? $_SESSION['color_filter'] : '';

// สร้างคำสั่ง SQL สำหรับแสดงสินค้าหมวดกางเกงตามตัวกรอง
$sql = "SELECT Product.P_Name, Product.Price, Product.Color, Images.IMG_path 
        FROM Product 
        INNER JOIN Images ON Product.IMG_ID = Images.IMG_ID
        WHERE Product.C_ID = ?"; // กรองสินค้าตามหมวดหมู่กางเกง

// เพิ่มเงื่อนไขในการกรองตามราคาและสี
$params = [$pants_category_id]; // ค่าเริ่มต้นคือหมวดหมู่กางเกง
if ($min_price > 0) {
    $sql .= " AND Product.Price >= ?";
    $params[] = $min_price;
}
if ($max_price > 0) {
    $sql .= " AND Product.Price <= ?";
    $params[] = $max_price;
}
if ($color) {
    $sql .= " AND LOWER(Product.Color) = ?";  // ใช้ LOWER เพื่อไม่สนใจตัวพิมพ์เล็ก/ใหญ่
    $params[] = $color;
}

// เตรียมและรันคำสั่ง SQL
$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
    $products = $stmt->fetchAll();
} else {
    // แสดงข้อผิดพลาดถ้า query ไม่สำเร็จ
    print_r($stmt->errorInfo());
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - เสื้อ</title>
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

        /* สินค้าทั้งหมด */
        .product-list {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 2rem;
            width: 75%;
            padding: 20px;
            translate: 30% -25rem;
        }

        .filter {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
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

    <div class="shop-container">

        <aside class="filter-sidebar">
            <h3>กรองสินค้า</h3>
            <form action="Pants.php" method="POST">
                <br><br>
                <h3>ค้นหาสินค้า</h3>
                <div class="search-section">
                    <input type="text" name="search_query" placeholder="ค้นหาชื่อสินค้า...">
                </div>
                
                <div class="filter-price">
                    <label for="min_price">ราคาต่ำสุด (บาท)</label>
                    <input type="number" name="min_price" id="min_price" min="0">
                    <label for="max_price">ราคาสูงสุด (บาท)</label>
                    <input type="number" name="max_price" id="max_price" min="0">
                </div>
                <div class="filter-color">
                    <label for="color">สี</label>
                    <input type="text" name="color" id="color" placeholder="กรอกชื่อสี">
                </div>
                <button type="submit" class="filter">กรองสินค้า</button>
            </form>
        </aside>

        <!-- แสดงรายการสินค้า -->
        <section class="product-list">
            <?php if (count($products) > 0): ?>
                <?php foreach ($products as $product): ?>
                    <div class="product-item">
                        <img src="<?php echo $product['IMG_path']; ?>"
                            alt="<?php echo htmlspecialchars($product['P_Name']); ?>">
                        <h4>
                            <?php echo htmlspecialchars($product['P_Name']); ?>
                        </h4>
                        <p>฿
                            <?php echo number_format($product['Price'], 2); ?>
                        </p>
                        <a href="#" class="btn add-to-cart" 
                           data-name="<?php echo htmlspecialchars($product['P_Name']); ?>" 
                           data-price="<?php echo number_format($product['Price'], 2); ?>">
                           เพิ่มในรถเข็น</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="translate: 100%; ">ไม่พบสินค้าที่ตรงกับการกรองของคุณ</p>
            <?php endif; ?>
        </section>
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

        // เพิ่มสินค้าลงในรถเข็น
        const addToCartButtons = document.querySelectorAll('.add-to-cart');
        addToCartButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productName = button.dataset.name;
                const productPrice = button.dataset.price;

                const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

                // ตรวจสอบว่ามีสินค้าในรถเข็นแล้วหรือยัง
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
    </script>
</body>

</html>