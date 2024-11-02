<?php
<<<<<<< HEAD
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include 'connect.php'; // เชื่อมต่อฐานข้อมูล
=======

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'connect.php'; // เชื่อมต่อกับฐานข้อมูล
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c

// ตรวจสอบว่าผู้ใช้ได้เข้าสู่ระบบหรือยัง
if (!isset($_SESSION["Username"])) {
  header("Location: ../Sign-In/signin.php");
  exit();
}

$username = htmlspecialchars($_SESSION["Username"]);

<<<<<<< HEAD
// ตรวจสอบว่ามีการส่ง POST มาจริงหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category = isset($_POST['category']) && $_POST['category'] != 'ทั้งหมด' ? $_POST['category'] : '';
  $price = isset($_POST['price']) && $_POST['price'] != '' ? (int)$_POST['price'] : 0;

  // เก็บค่าการกรองใน session
  $_SESSION['category_filter'] = $category;
  $_SESSION['price_filter'] = $price;
=======
// ดึงหมวดหมู่สินค้าจากฐานข้อมูล
$category_sql = "SELECT C_ID, C_Name FROM Category";
$category_stmt = $pdo->prepare($category_sql);
$category_stmt->execute();
$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// ตรวจสอบว่ามีการส่ง POST มาจริงหรือไม่
// ตรวจสอบว่ามีการส่ง POST มาจริงหรือไม่
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $category = isset($_POST['category']) && $_POST['category'] != 'ทั้งหมด' ? $_POST['category'] : '';
  $min_price = isset($_POST['min_price']) && $_POST['min_price'] != '' ? (int) $_POST['min_price'] : 0;
  $max_price = isset($_POST['max_price']) && $_POST['max_price'] != '' ? (int) $_POST['max_price'] : 0;
  $color = isset($_POST['color']) && $_POST['color'] != '' ? strtolower($_POST['color']) : ''; // เปลี่ยนให้เป็นตัวพิมพ์เล็กทั้งหมด
  $search_query = isset($_POST['search_query']) ? trim($_POST['search_query']) : ''; // ค้นหาจาก Search Bar

  // เก็บค่าการกรองใน session
  $_SESSION['category_filter'] = $category;
  $_SESSION['min_price_filter'] = $min_price;
  $_SESSION['max_price_filter'] = $max_price;
  $_SESSION['color_filter'] = $color;
  $_SESSION['search_query'] = $search_query;
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c

  // รีไดเรกต์ไปที่หน้า shop.php (เพื่อแก้ปัญหาการส่งฟอร์มซ้ำ)
  header("Location: shop.php");
  exit();
}

// นำค่าจาก session มาใช้ (ถ้ามี)
$category = isset($_SESSION['category_filter']) ? $_SESSION['category_filter'] : '';
<<<<<<< HEAD
$price = isset($_SESSION['price_filter']) ? (int)$_SESSION['price_filter'] : 0;

// สร้างคำสั่ง SQL สำหรับแสดงสินค้าตามตัวกรอง
$sql = "SELECT Product.P_ID, Product.Name, Product.Price, Images.IMG_path, Category.C_Name 
        FROM Product 
        INNER JOIN Images ON Product.IMG_ID = Images.IMG_ID
        INNER JOIN Category ON Product.C_ID = Category.C_ID";

// เพิ่มเงื่อนไขในการกรองตามหมวดหมู่และราคา
=======
$min_price = isset($_SESSION['min_price_filter']) ? (int) $_SESSION['min_price_filter'] : 0;
$max_price = isset($_SESSION['max_price_filter']) ? (int) $_SESSION['max_price_filter'] : 0;
$color = isset($_SESSION['color_filter']) ? $_SESSION['color_filter'] : '';
$search_query = isset($_SESSION['search_query']) ? $_SESSION['search_query'] : '';

// สร้างคำสั่ง SQL สำหรับแสดงสินค้าตามตัวกรอง
$sql = "SELECT Product.P_ID, Product.P_Name, Product.Price, Product.Color, Images.IMG_path 
      FROM Product 
      INNER JOIN Images ON Product.IMG_ID = Images.IMG_ID";

// เพิ่มเงื่อนไขในการกรองตามหมวดหมู่, ราคา, สี และคำค้นหา
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
$conditions = [];
$params = [];

if ($category) {
<<<<<<< HEAD
  $conditions[] = "Category.C_Name = ?";
  $params[] = $category;
}
if ($price > 0) {
  $conditions[] = "Product.Price >= ?";
  $params[] = $price;
}

=======
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
  $params[] = "%" . $search_query . "%";  // ค้นหาด้วยคำค้นหาในชื่อสินค้า
}

// ถ้ามีเงื่อนไข ให้เพิ่ม WHERE ใน SQL query
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
if (count($conditions) > 0) {
  $sql .= " WHERE " . implode(" AND ", $conditions);
}

<<<<<<< HEAD
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



=======
// เตรียมและรันคำสั่ง SQL
$stmt = $pdo->prepare($sql);
if ($stmt->execute($params)) {
  $products = $stmt->fetchAll();
} else {
  // แสดงข้อผิดพลาดถ้า query ไม่สำเร็จ
  print_r($stmt->errorInfo());
}

?>

>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
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
<<<<<<< HEAD
      translate: 30% -16rem;
=======
      translate: 30% -30rem;
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
    }

    .filter {
      padding: 10px 20px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 4px;
<<<<<<< HEAD
    }

    .add-to-cart {
      padding: 10px 20px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 4px;
    }

    .info {
      padding: 10px 20px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      border-radius: 4px;
      text-decoration: none;
    }

    .product-item p {
      color: var(--primary-color);
      font-weight: bold;
      min-height: 3rem;
    }
=======
      cursor: pointer;
    }

    .search-section button {
      padding: 15px 10px;
      background-color: var(--primary-color);
      color: white;
      border: none;
      cursor: pointer;
    }

>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
  </style>
</head>

<body>
<<<<<<< HEAD
  <?php
=======
<?php
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
  // รีเซตการกรองสินค้าเมื่อผู้ใช้เข้ามาหน้าร้านค้าใหม่
  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    unset($_SESSION['category_filter']);
    unset($_SESSION['price_filter']);
  }

  ?>
  <!-- ส่วนหัว (Header) -->
  <header>
<<<<<<< HEAD
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
=======
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
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c

  <!-- Blur Background -->
  <div class="blur-background"></div>

  <div class="shop-container">
<<<<<<< HEAD
    <!-- ส่วนค้นหาสินค้า -->
    <div class="search-section">
      <input type="text" placeholder="ค้นหาสินค้า...">
      <button>ค้นหา</button>
    </div>
=======

>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
    <!-- ฟอร์มกรองสินค้า -->
    <aside class="filter-sidebar">
      <h3>กรองสินค้า</h3>
      <form action="shop.php" method="POST">
<<<<<<< HEAD
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
=======
      <br><br><h3>ค้นหาสินค้า</h3>
        <div class="search-section">
            <input type="text" name="search_query" placeholder="ค้นหาชื่อสินค้า...">
          </div>
        <div class="filter-category">
          <label for="category">หมวดหมู่</label>
          <select name="category" id="category">
            <option value="ทั้งหมด">ทั้งหมด</option>
            <!-- ดึงหมวดหมู่จากฐานข้อมูล -->
            <?php foreach ($categories as $cat): ?>
              <option value="<?php echo $cat['C_ID']; ?>">
                <?php echo $cat['C_Name']; ?>
              </option>
            <?php endforeach; ?>
          </select>
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
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
        </div>
        <button type="submit" class="filter">กรองสินค้า</button>
      </form>
    </aside>

    <!-- แสดงรายการสินค้า -->
    <section class="product-list">
      <?php if (count($products) > 0): ?>
        <?php foreach ($products as $product): ?>
          <div class="product-item">
<<<<<<< HEAD
            <img src="<?php echo $product['IMG_path']; ?>" alt="<?php echo htmlspecialchars($product['Name']); ?>">
            <h4><?php echo htmlspecialchars($product['Name']); ?></h4>
            <p>฿<?php echo number_format($product['Price'], 2); ?></p>
            <!-- เปลี่ยนปุ่มเพิ่มในรถเข็นเป็นลิงค์ไปยังหน้า product-detail.php -->
            <a href="../Product-detail/product-detail.php?id=<?php echo $product['P_ID']; ?>" class="info">ดูรายละเอียด</a>
=======
            <img src="<?php echo $product['IMG_path']; ?>" alt="<?php echo htmlspecialchars($product['P_Name']); ?>">
            <h4>
              <?php echo htmlspecialchars($product['P_Name']); ?>
            </h4>
            <p>฿
              <?php echo number_format($product['Price'], 2); ?>
            </p>
            <a href="../Product-detail/product-detail.php?id=<?php echo $product['P_ID']; ?>" class="info">ดูรายละเอียด</a>
            <a href="#" class="btn add-to-cart" 
                           data-name="<?php echo htmlspecialchars($product['P_Name']); ?>" 
                           data-price="<?php echo number_format($product['Price'], 2); ?>"
                           data-img="<?php echo $product['IMG_path']; ?>"
                            data-id="<?php echo $product['P_ID']; ?>">
                           เพิ่มในรถเข็น</a>
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
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
<<<<<<< HEAD
    addToCartButtons.forEach(button => {
      button.addEventListener('click', () => {
        const productId = button.dataset.id;
        const productName = button.dataset.name;
        const productPrice = button.dataset.price;

        if (!productId || !productName || !productPrice) {
          console.error('ข้อมูลสินค้าบางส่วนไม่สมบูรณ์:', productId, productName, productPrice);
          alert('ไม่สามารถเพิ่มสินค้านี้ได้ กรุณาลองใหม่อีกครั้ง');
          return;
        }

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
    });
=======
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
                        id: productId,        // บันทึก id
                        name: productName,
                        price: productPrice,
                        img: productImage,    // บันทึก img
                        quantity: 1
                    });
                }

                localStorage.setItem('cartItems', JSON.stringify(cartItems));
                alert(`${productName} ถูกเพิ่มในรถเข็น`);
            });
        });
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
  </script>
</body>

</html>