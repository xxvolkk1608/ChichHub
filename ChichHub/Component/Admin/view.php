<?php
session_start();
include 'connect.php';  // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่ามีการล็อกอินและเป็น Admin หรือไม่
if (!isset($_SESSION["Role"]) || $_SESSION["Role"] != 1) {
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

// คำสั่ง SQL ที่ 1
$sql1 = "SELECT Product.P_name, Category.C_Name, Ord_detail.Amount, Orders.Date, Member.Username
         FROM Orders
         INNER JOIN Member ON Orders.ID = Member.Id 
         INNER JOIN Ord_detail ON Orders.Ord_ID = Ord_detail.Ord_ID 
         INNER JOIN Product ON Ord_detail.P_ID = Product.P_ID 
         INNER JOIN Category ON Product.C_ID = Category.C_ID";
$stmt1 = $pdo->prepare($sql1);
$stmt1->execute();
$results1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);

// คำสั่ง SQL ที่ 2
$sql2 = "SELECT Member.Username, Orders.Date, Orders.TIME, SUM(Product.price * Ord_detail.Amount) as price 
         FROM Orders 
         INNER JOIN Member ON Orders.ID = Member.Id 
         INNER JOIN Ord_detail ON Orders.Ord_ID = Ord_detail.Ord_ID 
         INNER JOIN Product ON Ord_detail.P_ID = Product.P_ID 
         INNER JOIN Category ON Product.C_ID = Category.C_ID 
         GROUP BY Member.Username";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$results2 = $stmt2->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order and Sales Information</title>
    <link rel="stylesheet" href="../styles/styles.css">
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
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 1000px;
            margin: 5rem auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
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
        h1, h2 {
            color: #333;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
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
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .btn-back {
            display: inline-block;
            background-color: #ff6347;
            color: #fff;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            border: none;
            margin-top: 20px;
            margin-left: 45em;
        }
        .btn-back:hover {
            background-color: #ff4500;
        }

        /* Styles for Tab Bar */
        .tab-bar {
            display: flex;
            justify-content: center;
            margin-bottom: 20px;
            border-bottom: 2px solid #ddd;
        }
        .tab-bar button {
            padding: 10px 20px;
            cursor: pointer;
            background-color: #fff;
            border: none;
            border-bottom: 3px solid transparent;
            font-size: 16px;
            color: #555;
            transition: 0.3s;
        }
        .tab-bar button.active {
            border-bottom: 3px solid #ff5722;
            color: #ff5722;
            font-weight: bold;
        }
        .tab-content {
            display: none;
        }
        .tab-content.active {
            display: block;
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

    <div class="container">
        <h1>ข้อมูลการสั่งซื้อและยอดขาย</h1>

        <!-- Tab Bar -->
        <div class="tab-bar">
            <button class="tab-button active" onclick="openTab(event, 'category-info')">ข้อมูลหมวดหมู่สินค้า</button>
            <button class="tab-button" onclick="openTab(event, 'date-info')">ข้อมูลวันที่และยอดขาย</button>
        </div>

        <!-- Tab Content for Category Information -->
        <div id="category-info" class="tab-content active">
            <h2>ข้อมูลหมวดหมู่สินค้า ปริมาณสินค้า และชื่อผู้ใช้</h2>
            <table>
                <thead>
                    <tr>
                        <th>หมวดหมู่สินค้า</th>
                        <th>ชื่อสินค้า</th>
                        <th>จำนวนสินค้า</th>
                        <th>วันที่ซื้อ</th>
                        <th>ชื่อผู้ใช้</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results1 as $row1): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row1['C_Name']); ?></td>
                            <td><?php echo htmlspecialchars($row1['P_name']); ?></td>
                            <td><?php echo htmlspecialchars($row1['Amount']); ?></td>
                            <td><?php echo htmlspecialchars($row1['Date']); ?></td>
                            <td><?php echo htmlspecialchars($row1['Username']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <!-- Tab Content for Date and Sales Information -->
        <div id="date-info" class="tab-content">
            <h2>ข้อมูลวันที่ เวลา และยอดรวม</h2>
            <table>
                <thead>
                    <tr>
                        <th>ชื่อผู้ใช้</th>
                        <th>วันที่ซื้อล่าสุด</th>
                        <th>เวลาที่ซื้อล่าสุด</th>
                        <th>ยอดรวม (บาท)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results2 as $row2): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row2['Username']); ?></td>
                            <td><?php echo htmlspecialchars($row2['Date']); ?></td>
                            <td><?php echo htmlspecialchars($row2['TIME']); ?></td>
                            <td>฿<?php echo number_format($row2['price'], 2); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <a href="../Home/home.php" class="btn-back">กลับสู่หน้าหลัก</a>
    </div>

    <!-- ส่วนท้าย (Footer) -->
    <footer style="background-color: #333; color: #fff; text-align: center; padding: 10px; margin-top: 20px;">
        <p>&copy; 2024 ChicHub. All Rights Reserved.</p>
    </footer>

    <script>
        function openTab(evt, tabId) {
            // ซ่อนทุก tab-content
            var tabContents = document.getElementsByClassName("tab-content");
            for (var i = 0; i < tabContents.length; i++) {
                tabContents[i].classList.remove("active");
            }

            // นำเอาคลาส active ออกจากทุกปุ่ม
            var tabButtons = document.getElementsByClassName("tab-button");
            for (var i = 0; i < tabButtons.length; i++) {
                tabButtons[i].classList.remove("active");
            }

            // แสดง tab-content ที่ถูกเลือก และเพิ่ม active ให้ปุ่ม
            document.getElementById(tabId).classList.add("active");
            evt.currentTarget.classList.add("active");
        }
    </script>
    
</body>
</html>
