<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามี session อยู่หรือไม่
if (!isset($_SESSION["Username"])) {
    if (isset($_COOKIE["Username"])) {
        // ตั้งค่า session ใหม่จาก cookies
        $_SESSION["Username"] = $_COOKIE["Username"];
    }
}

// ถ้าไม่มีทั้ง session และ cookies ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
if (!isset($_SESSION["Username"])) {
    header("Location: ../Sign-In/signin.php");
    exit();
}

// ดึงข้อมูล Role ของผู้ใช้จากฐานข้อมูล
$username = htmlspecialchars($_SESSION["Username"]);
$stmt = $pdo->prepare("SELECT Role FROM Member WHERE Username = ?");
$stmt->execute([$username]);
$user = $stmt->fetch();

// แสดงชื่อผู้ใช้
echo "สวัสดี, $username";


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
    </style>
</head>

<body>
<?php
    include 'connect.php'; // เชื่อมต่อฐานข้อมูล

    // ดึงข้อมูลสินค้ายอดนิยมและรูปภาพจากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT Product.P_Name, Product.Price, Images.IMG_path 
                                  FROM Product 
                                  INNER JOIN Images ON Product.IMG_ID = Images.IMG_ID
                                  WHERE Product.P_Name LIKE 'Pant%' 
                                  OR Product.P_Name LIKE 'Shirt%';
    ");
    $stmt->execute();
    $products = $stmt->fetchAll();

    $stmt = $pdo->prepare("SELECT B_Name, B_img FROM Banner");
    $stmt->execute();
    $Banner = $stmt->fetch();
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

    <!-- ส่วนแบนเนอร์โปรโมชั่น -->
    <section class="banner">
        <div class="container">
            <div class="banner-image">
                <img src="<?php echo $Banner['B_img']; ?>" alt="<?php echo $Banner['B_Name']; ?>">
                <div class="banner-text">
                    <h2><?php echo $Banner['B_Name']; ?></h2>
                    <a href="../Category/Promotion.php" class="btn">ช้อปเลย</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ส่วนแสดงสินค้า -->
    <section class="featured-products">
        <div class="container">
            <h2>สินค้ายอดนิยม</h2>
            <div class="products">
                <?php foreach ($products as $product): ?>
                    <div class="product-card">
                        <img src="<?php echo $product['IMG_path']; ?>" alt="<?php echo htmlspecialchars($product['P_Name']); ?>">
                        <h3><?php echo htmlspecialchars($product['P_Name']); ?></h3>
                        <p>฿<?php echo number_format($product['Price'], 2); ?></p>
                        <a href="#" class="btn add-to-cart" 
                           data-name="<?php echo htmlspecialchars($product['P_Name']); ?>" 
                           data-price="<?php echo number_format($product['Price'], 2); ?>">
                           เพิ่มในรถเข็น</a>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </section>

    <!-- ส่วนลิงก์ไปยังหมวดหมู่สินค้า -->
    <section class="categories">
        <div class="container">
            <h2>หมวดหมู่สินค้า</h2>
            <div class="category-list">
                <div class="category-item">
                    <img class="tshirt" src="../cat_img/tshirt.png" alt="">
                    <a href="../Category/Shirt.php">เสื้อยืด</a>
                </div>
                <div class="category-item">
                    <img class="pant" src="../cat_img/pant.png" alt="">
                    <a href="../Category/Pants.php">กางเกง</a>
                </div>
                <div class="category-item">
                    <img class="jacket" src="../cat_img/promo.jpeg" alt="">
                    <a href="../Category/Promotion.php">Promotion</a>
                </div>
            </div>
        </div>
    </section>

    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="#">เกี่ยวกับเรา</a>
                <a href="../Policy/privacy_policy.html">นโยบายความเป็นส่วนตัว</a>
                <a href="#">เงื่อนไขการใช้งาน</a>
                <a href="../Contact-us/contact-us.php">ติดต่อเรา</a>
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
