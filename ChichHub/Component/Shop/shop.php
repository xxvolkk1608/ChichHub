<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION["Username"])) {
  header("Location: ../Sign-In/signin.php");
  exit();
}

$username = htmlspecialchars($_SESSION["Username"]);

// ตรวจสอบว่ามีการส่ง POST มาจริงหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category = isset($_POST['category']) && $_POST['category'] != 'ทั้งหมด' ? $_POST['category'] : '';
  $price = isset($_POST['price']) && $_POST['price'] != '' ? (int)$_POST['price'] : 0;

  // เก็บค่าการกรองใน session
  $_SESSION['category_filter'] = $category;
  $_SESSION['price_filter'] = $price;

  // รีไดเรกต์ไปที่หน้า shop.php (เพื่อแก้ปัญหาการส่งฟอร์มซ้ำ)
  header("Location: shop.php");
  exit();
}

// นำค่าจาก session มาใช้ (ถ้ามี)
$category = isset($_SESSION['category_filter']) ? $_SESSION['category_filter'] : '';
$price = isset($_SESSION['price_filter']) ? (int)$_SESSION['price_filter'] : 0;

// สร้างคำสั่ง SQL สำหรับแสดงสินค้าตามตัวกรอง
$sql = "SELECT Product.Name, Product.Price, Images.IMG_path, Category.C_Name 
        FROM Product 
        INNER JOIN Images ON Product.IMG_ID = Images.IMG_ID
        INNER JOIN Category ON Product.C_ID = Category.C_ID";

// เพิ่มเงื่อนไขในการกรองตามหมวดหมู่และราคา
$conditions = [];
$params = [];

if ($category) {
  $conditions[] = "Category.C_Name = ?";
  $params[] = $category;
}
if ($price > 0) {
  $conditions[] = "Product.Price >= ?";
  $params[] = $price;
}

if (count($conditions) > 0) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}

// Debugging: แสดงค่า SQL query ที่สร้างขึ้น
// echo "<pre>";
// echo "SQL: " . $sql . "\n";
// echo "Params: " . print_r($params, true) . "\n";
// echo "</pre>";
// เตรียมและรันคำสั่ง SQL
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll();
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
      translate: 30% -16rem;
    }

    .filter {
      padding: 10px 20px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 4px;
    }

    .add-to-cart {
      padding: 10px 20px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 4px;
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
          <li><a href="#">โปรโมชั่น</a></li>
          <li><a href="../Contact-us/contact-us.php">ติดต่อเรา</a></li>
          <li class="dropdown">
            <a href="#"><i class="fas fa-user"></i> สวัสดี, <?php echo $username; ?></a>
            <div class="dropdown-content">
              <a href="../User/edit_profile.php">แก้ไขข้อมูลส่วนตัว</a>
              <a style="color: red;" href="#" onclick="confirmLogout()">ออกจากระบบ</a>
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
    <!-- ส่วนค้นหาสินค้า -->
    <div class="search-section">
      <input type="text" placeholder="ค้นหาสินค้า...">
      <button>ค้นหา</button>
    </div>
    <!-- ฟอร์มกรองสินค้า -->
    <aside class="filter-sidebar">
      <h3>กรองสินค้า</h3>
      <form action="shop.php" method="POST">
        <div class="filter-category">
          <label for="category">หมวดหมู่</label>
          <select name="category" id="category">
            <option value="ทั้งหมด" <?php if ($category == 'ทั้งหมด') echo 'selected'; ?>>ทั้งหมด</option>
            <option value="เสื้อยืด" <?php if ($category == 'เสื้อยืด') echo 'selected'; ?>>เสื้อยืด</option>
            <option value="เสื้อเชิ้ต" <?php if ($category == 'เสื้อเชิ้ต') echo 'selected'; ?>>เสื้อเชิ้ต</option>
            <option value="กางเกง" <?php if ($category == 'กางเกง') echo 'selected'; ?>>กางเกง</option>
            <option value="เสื้อแจ็คเก็ต" <?php if ($category == 'เสื้อแจ็คเก็ต') echo 'selected'; ?>>เสื้อแจ็คเก็ต</option>
          </select>
        </div>
        <div class="filter-price">
          <label for="price">ราคา (บาท)</label>
          <input type="number" name="price" id="price" min="0" value="<?php echo $price; ?>">
        </div>
        <button type="submit" class="filter">กรองสินค้า</button>
      </form>
    </aside>

    <!-- แสดงรายการสินค้า -->
    <section class="product-list">
      <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
          <div class="product-item">
            <img src="<?php echo $product['IMG_path']; ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>">
            <h4><?php echo htmlspecialchars($product['Name']); ?></h4>
            <p>฿<?php echo number_format($product['Price'], 2); ?></p>
            <button class="add-to-cart" data-name="<?php echo htmlspecialchars($product['Name']); ?>" data-price="<?php echo $product['Price']; ?>">เพิ่มในรถเข็น</button>
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