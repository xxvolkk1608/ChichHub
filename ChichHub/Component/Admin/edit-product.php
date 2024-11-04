<?php
session_start();
include 'connect.php';  // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่าผู้ใช้เป็นแอดมินหรือไม่
if (!isset($_SESSION["Role"]) || $_SESSION["Role"] != 1) {
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

// ดึงข้อมูลสินค้าทั้งหมดจากฐานข้อมูล
$products_stmt = $pdo->prepare("SELECT Product.*, Images.IMG_path, Category.C_Name 
                                FROM Product 
                                JOIN Images ON Product.IMG_ID = Images.IMG_ID
                                JOIN Category ON Product.C_ID = Category.C_ID");
$products_stmt->execute();
$products = $products_stmt->fetchAll(PDO::FETCH_ASSOC);

// ดึงข้อมูลหมวดหมู่จากฐานข้อมูล
$category_stmt = $pdo->prepare("SELECT * FROM Category");
$category_stmt->execute();
$categories = $category_stmt->fetchAll(PDO::FETCH_ASSOC);

// ลบสินค้าจากฐานข้อมูล
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $delete_stmt = $pdo->prepare("DELETE FROM Product WHERE P_ID = ?");
    $delete_stmt->execute([$delete_id]);
    header("Location: edit-product.php");
    exit();
}

// แก้ไขสินค้าหลังจากส่งฟอร์ม
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_product'])) {
    $product_id = $_POST['product_id'];
    $P_name = $_POST['P_name'];
    $price = $_POST['price'];
    $amount = $_POST['amount'];
    $color = $_POST['color'];
    $category_id = $_POST['category'];
    $detail = $_POST['detail'];

    $update_stmt = $pdo->prepare("UPDATE Product 
                                  SET P_name = ?, Price = ?, Amount = ?, Color = ?, C_ID = ?, Detail = ? 
                                  WHERE P_ID = ?");
    $update_stmt->execute([$P_name, $price, $amount, $color, $category_id, $detail, $product_id]);

    header("Location: edit-product.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="th">

<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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

    .container-userinfo {
        max-width: 900px;
        margin-top: 7rem;
        margin-left: auto;
        margin-bottom: 2rem;
        margin-right: auto;
        background-color: #fff;
        padding: 30px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .input-group {
        margin-bottom: 20px;
    }

    .input-group label {
        display: block;
        font-size: 16px;
        margin-bottom: 5px;
        color: #333;
    }

    .input-group input,
    .input-group textarea {
        width: 100%;
        padding: 10px;
        font-size: 16px;
        border-radius: 5px;
        border: 1px solid #ddd;
        box-sizing: border-box;
    }

    .btn {
        background-color: #ff6347;
        color: #fff;
        padding: 12px 20px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        border-radius: 5px;
        cursor: pointer;
        transition: background-color 0.3s;
        border: none;
        margin-left: 45%;
    }

    .btn:hover {
        background-color: #ff4500;
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
    
    /* สไตล์ตารางรายการสินค้า */
    .product-list {
        max-width: 1000px;
        margin: 5rem auto;
        
        padding: 20px;
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .product-list h2 {
        text-align: center;
        font-size: 24px;
        color: #333;
        margin-bottom: 1rem;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        text-align: center;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #ff5722;
        color: #fff;
        font-weight: bold;
        margin-top: 3em;
    }

    tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    /* สไตล์สำหรับรูปสินค้า */
    .product-list img {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 4px;
    }

    /* สไตล์ลิงก์การกระทำ */
    .actions a {
        padding: 5px 10px;
        margin: 0 5px;
        color: #ffffff;
        background-color: #ff6347;
        border-radius: 4px;
        font-size: 0.9rem;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .actions a:hover {
        background-color: #ff4500;
    }
    .edit-product-form{
        margin: 8em ;
    }
   

    /* ปรับสไตล์ให้กับการแสดงรายการในหน้าจอขนาดเล็ก */
    @media (max-width: 768px) {
        th, td {
            font-size: 0.9rem;
            padding: 8px;
        }

        .product-list img {
            width: 50px;
            height: 50px;
        }
    
    }
</style>

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
                    <li><a href="#">โปรโมชั่น</a></li>
                    <li><a href="../Contact-us/contact-us.php">ติดต่อเรา</a></li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-user"></i> สวัสดี,
                            <?php echo $_SESSION['Username']; ?>
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
    <?php
    // กดปุ่ม "แก้ไข" ให้แสดงฟอร์มสำหรับแก้ไขสินค้า
    if (isset($_GET['edit_id'])):
        $edit_id = $_GET['edit_id'];
        $edit_stmt = $pdo->prepare("SELECT * FROM Product WHERE P_ID = ?");
        $edit_stmt->execute([$edit_id]);
        $product = $edit_stmt->fetch(PDO::FETCH_ASSOC);
        
        // ตรวจสอบว่าข้อมูลถูกดึงมาได้หรือไม่
        if (!$product) {
            echo "<p>ไม่พบสินค้าที่คุณต้องการแก้ไข</p>";
            exit();
        }
    ?>
        <section class="edit-product-form">
            <h2>แก้ไขสินค้า: <?php echo htmlspecialchars($product['P_name']); ?></h2>
            <form method="POST" action="edit-product.php">
                <input type="hidden" name="product_id" value="<?php echo $product['P_ID']; ?>">
                <input type="hidden" name="edit_product" value="1">

                <div class="input-group">
                    <label for="P_name">ชื่อสินค้า</label>
                    <input type="text" name="P_name" id="P_name" value="<?php echo htmlspecialchars($product['P_name']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="price">ราคา (บาท)</label>
                    <input type="number" name="price" id="price" value="<?php echo htmlspecialchars($product['Price']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="amount">จำนวน</label>
                    <input type="number" name="amount" id="amount" value="<?php echo htmlspecialchars($product['Amount']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="color">สี</label>
                    <input type="text" name="color" id="color" value="<?php echo htmlspecialchars($product['Color']); ?>" required>
                </div>

                <div class="input-group">
                    <label for="category">หมวดหมู่</label>
                    <select name="category" id="category" required>
                        <?php foreach ($categories as $category): ?>
                            <option value="<?php echo $category['C_ID']; ?>" <?php echo $product['C_ID'] == $category['C_ID'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($category['C_Name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="input-group">
                    <label for="detail">รายละเอียดสินค้า</label>
                    <textarea name="detail" id="detail" required><?php echo htmlspecialchars($product['Detail']); ?></textarea>
                </div>

                <button class="btn" type="submit">บันทึกการแก้ไข</button>
            </form>
        </section>
    <?php endif; ?>

    <section class="product-list">
        <h2>รายการสินค้า</h2>
        <table>
            <thead>
                <tr>
                    <th>รูปภาพ</th>
                    <th>ชื่อสินค้า</th>
                    <th>ราคา</th>
                    <th>จำนวน</th>
                    <th>สี</th>
                    <th>หมวดหมู่</th>
                    <th>รายละเอียด</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td><img src="<?php echo $product['IMG_path']; ?>" width="50" height="50"></td>
                        <td><?php echo htmlspecialchars($product['P_name']); ?></td>
                        <td>฿<?php echo number_format($product['Price'], 2); ?></td>
                        <td><?php echo $product['Amount']; ?></td>
                        <td><?php echo htmlspecialchars($product['Color']); ?></td>
                        <td><?php echo htmlspecialchars($product['C_Name']); ?></td>
                        <td><?php echo htmlspecialchars($product['Detail']); ?></td>
                        <td>
                            <a href="edit-product.php?edit_id=<?php echo $product['P_ID']; ?> ">แก้ไข</a> |
                            <a href="edit-product.php?delete_id=<?php echo $product['P_ID']; ?>" onclick="return confirm('คุณแน่ใจว่าต้องการลบสินค้านี้?');">ลบ</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </section>

   
</body>
</html>
