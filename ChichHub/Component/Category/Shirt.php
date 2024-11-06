<?php
session_start();
include 'connect.php'; // เชื่อมต่อกับฐานข้อมูล

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION["Username"])) {
    header("Location: ../Sign-In/signin.php");
    exit();
}

// ตรวจสอบว่ามีการตั้งค่าคุกกี้ user_login หรือไม่
if (!isset($_COOKIE['user_login'])) {
    session_unset();
    session_destroy();
    setcookie("user_login", "", time() - 1800, "/");
    header("Location: ../Sign-In/signin.php");
    exit();
}

$username = htmlspecialchars($_SESSION["Username"]);

// ดึงหมวดหมู่สินค้าจากฐานข้อมูล
$category_sql = "SELECT C_ID, C_Name FROM Category";
$category_stmt = $pdo->prepare($category_sql);
$category_stmt->execute();
$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบค่าจาก POST และเก็บตัวกรองใน session
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['category_filter'] = isset($_POST['category']) ? $_POST['category'] : '';
    $_SESSION['min_price_filter'] = isset($_POST['min_price']) ? (int) $_POST['min_price'] : 0;
    $_SESSION['max_price_filter'] = isset($_POST['max_price']) ? (int) $_POST['max_price'] : 0;
    $_SESSION['color_filter'] = isset($_POST['color']) ? strtolower($_POST['color']) : '';
    $_SESSION['search_query'] = isset($_POST['search_query']) ? trim($_POST['search_query']) : '';
}

// กำหนดค่าตัวกรองจาก session
$category = $_SESSION['category_filter'] ?? '';
$min_price = $_SESSION['min_price_filter'] ?? 0;
$max_price = $_SESSION['max_price_filter'] ?? 0;
$color = $_SESSION['color_filter'] ?? '';
$search_query = $_SESSION['search_query'] ?? '';

// กำหนดค่าการแบ่งหน้า
$items_per_page = 12;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$offset = ($page - 1) * $items_per_page;

// สร้างคำสั่ง SQL สำหรับแสดงสินค้าตามตัวกรอง
$sql = "SELECT Product.P_ID, Product.P_Name, Product.Price, Product.Color, Images.IMG_path 
        FROM Product 
        INNER JOIN Images ON Product.IMG_ID = Images.IMG_ID 
        WHERE Product.C_ID = 1001";  // เพิ่มเงื่อนไขกรองเฉพาะ C_ID = 1001

$conditions = [];
$params = [];

if ($category && $category != 'ทั้งหมด') {
    $conditions[] = "Product.C_ID = ?";
    $params[] = $category;
}
if ($min_price > 0) {
    $conditions[] = "Product.Price >= ?";
    $params[] = $min_price;
}
if ($max_price > 0) {
    $conditions[] = "Product.Price <= ?";
    $params[] = $max_price;
}
if ($color) {
    $conditions[] = "LOWER(Product.Color) = ?";
    $params[] = $color;
}
if ($search_query) {
    $conditions[] = "Product.P_Name LIKE ?";
    $params[] = "%" . $search_query . "%";
}

// เชื่อมเงื่อนไขทั้งหมดกับ WHERE
if (count($conditions) > 0) {
    $sql .= " AND " . implode(" AND ", $conditions);
}

$sql .= " LIMIT $items_per_page OFFSET $offset";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();

// นับจำนวนสินค้าทั้งหมดเพื่อใช้ในการสร้าง pagination
$count_sql = "SELECT COUNT(*) FROM Product WHERE Product.C_ID = 1001"; // นับเฉพาะสินค้าที่ C_ID = 1001
if (count($conditions) > 0) {
    $count_sql .= " AND " . implode(" AND ", $conditions);
}
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);
$count_stmt = $pdo->prepare($count_sql);
$count_stmt->execute($params);
$total_items = $count_stmt->fetchColumn();
$total_pages = ceil($total_items / $items_per_page);
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - ร้านค้า</title>
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
            translate: 30% -30rem;
            margin-bottom: -30rem;
        }

        .filter {
            padding: 10px 20px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        .search-section button {
            padding: 15px 10px;
            background-color: var(--primary-color);
            color: white;
            border: none;
            cursor: pointer;
        }

        .add-to-cart {
            padding: 10px 20px;
            background-color: #ff6f61;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .add-to-cart:hover {
            background: #e65b50;
        }

        .info {
            padding: 10px 20px;
            background-color: #ff6f61;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: background 0.3s ease;
        }

        .info:hover {
            background: #e65b50;
        }

        .pagination {
            display: flex;
            justify-content: center;
            margin-bottom: 3em;
        }

        .pagination a,
        .pagination span {
            padding: 10px 15px;
            margin: 0 5px;
            text-decoration: none;
            color: #333;
            border: 1px solid #ddd;
            border-radius: 5px;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a:hover {
            background-color: #ff6f61;
            color: #fff;
        }

        .pagination .active {
            background-color: #ff6f61;
            color: #fff;
            border-color: #ff6f61;
        }

        @media (max-width: 600px) {
            .product-list {
                display: grid;
                grid-template-columns: repeat(1, 1fr);
                gap: 2rem;
                width: 100%;
                padding: 20px;
                translate: 0% -32rem;
                margin-bottom: -30rem;
                margin-top: 40rem;
            }

            .filter-sidebar {
                width: 100%;
                padding: 30px;
                margin-top: 4rem;
                border-right: 0px solid #c5c5c5;
            }
        }
    </style>
</head>

<body>
    <?php
    // รีเซตการกรองสินค้าเมื่อผู้ใช้เข้ามาหน้าร้านค้าใหม่
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        unset($_SESSION['category_filter']);
        unset($_SESSION['price_filter']);
    }

    ?>
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

    <div class="shop-container">
        <!-- ฟอร์มกรองสินค้า -->
        <aside class="filter-sidebar">
            <h3>กรองสินค้า</h3>
            <form action="Shirt.php" method="POST">
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
                        <p style="color:#ff6f61; margin-bottom: 2vh;">฿
                            <?php echo number_format($product['Price'], 2); ?>
                        </p>
                        <a href="../Product-detail/product-detail.php?id=<?php echo $product['P_ID']; ?>"
                            class="info">ดูรายละเอียด</a>
                        <a href="#" class="add-to-cart" data-name="<?php echo htmlspecialchars($product['P_Name']); ?>"
                            data-price="<?php echo $product['Price']; ?>" data-img="<?php echo $product['IMG_path']; ?>"
                            data-id="<?php echo $product['P_ID']; ?>">
                            เพิ่มในรถเข็น</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p style="translate: 100%; ">ไม่พบสินค้าที่ตรงกับการกรองของคุณ</p>
            <?php endif; ?>
        </section>
        <!-- ปุ่มแบ่งหน้า -->
        <div class="pagination">
            <?php if ($page > 1): ?>
                <a href="?page=<?php echo $page - 1; ?>">&laquo; ก่อนหน้า</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                <?php if ($i == $page): ?>
                    <span class="active">
                        <?php echo $i; ?>
                    </span>
                <?php else: ?>
                    <a href="?page=<?php echo $i; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($page < $total_pages): ?>
                <a href="?page=<?php echo $page + 1; ?>">ถัดไป &raquo;</a>
            <?php endif; ?>
        </div>
    </div>
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
                const productId = button.getAttribute('data-id'); // ดึงค่า id จาก attribute
                const productName = button.getAttribute('data-name');
                const productPrice = button.getAttribute('data-price');
                const productImage = button.getAttribute('data-img'); // ดึงค่า img จาก attribute

                const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

                const existingItem = cartItems.find(item => item.name === productName);
                if (existingItem) {
                    existingItem.quantity += 1; // เพิ่มจำนวนสินค้า
                } else {
                    cartItems.push({
                        id: productId, // บันทึก id
                        name: productName,
                        price: productPrice,
                        img: productImage, // บันทึก img
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