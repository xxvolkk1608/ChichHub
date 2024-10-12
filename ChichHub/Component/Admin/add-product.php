<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
include 'connect.php';  // เชื่อมต่อกับฐานข้อมูล

$username = $_SESSION['Username'];

// ตรวจสอบว่ามีการล็อกอินและเป็น Admin หรือไม่
if (!isset($_SESSION["Role"]) || $_SESSION["Role"] != 1) {
    header("Location: ../Sign-In/signin.php");
    exit();
}

// ดึงข้อมูล Category ที่มีอยู่จากฐานข้อมูล
$stmt = $pdo->prepare("SELECT * FROM Category");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $pname = $_POST['pname']; // รับค่าจากฟอร์มสำหรับชื่อสินค้า
        $price = $_POST['price'];
        $amount = $_POST['amount'];
        $color = $_POST['color'];
        $category_id = $_POST['category'];
        $detail = $_POST['detail'];

        if (empty($pname) || empty($price) || empty($amount) || empty($color) || empty($category_id) || empty($detail)) {
            throw new Exception("กรุณากรอกข้อมูลทุกช่อง");
        }

        $category_stmt = $pdo->prepare("SELECT C_Name FROM Category WHERE C_ID = ?");
        $category_stmt->execute([$category_id]);
        $category = $category_stmt->fetchColumn();

        if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] == UPLOAD_ERR_OK) {
            $file_name = $_FILES['product_image']['name'];
            $file_tmp = $_FILES['product_image']['tmp_name'];

            $upload_dir = "/Applications/XAMPP/xamppfiles/htdocs/project/ChichHub/Component/img/$category/";
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);  
            }

            $img_path = "http://localhost/project/ChichHub/Component/img/$category/$file_name";

            if (move_uploaded_file($file_tmp, $upload_dir . $file_name)) {
                $stmt = $pdo->prepare("INSERT INTO Images (File_name, Upload_date, IMG_path) VALUES (?, NOW(), ?)");
                if ($stmt->execute([$file_name, $img_path])) {
                    $img_id = $pdo->lastInsertId();

                    // รวม pname ในคำสั่ง INSERT INTO Product
                    $stmt = $pdo->prepare("INSERT INTO Product (P_Name, Price, Amount, C_ID, Color, IMG_ID, Detail ) VALUES (?, ?, ?, ?, ?, ?, ?)");
                    if ($stmt->execute([$pname, $price, $amount, $category_id, $color, $img_id , $detail])) {
                        // เปลี่ยนเส้นทางหลังจากเพิ่มสินค้าเสร็จแล้ว เพื่อป้องกันการทำรายการซ้ำ
                        header("Location: add-product.php?status=success");
                        exit();
                    } else {
                        $message = "เกิดข้อผิดพลาดในการเพิ่มสินค้า: " . implode(" ", $stmt->errorInfo());
                    }
                } else {
                    $message = "เกิดข้อผิดพลาดในการเพิ่มข้อมูลรูปภาพ: " . implode(" ", $stmt->errorInfo());
                }
            } else {
                $message = "เกิดข้อผิดพลาดในการย้ายไฟล์รูปภาพ";
            }
        } else {
            $message = "เกิดข้อผิดพลาดในการอัปโหลดรูปภาพ: " . $_FILES['product_image']['error'];
        }
    } catch (PDOException $e) {
        $message = "เกิดข้อผิดพลาด: " . $e->getMessage();
    } catch (Exception $e) {
        $message = $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เพิ่มสินค้า</title>
    <link rel="stylesheet" href="../styles/styles.css">
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
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
        margin-left: 39%;
    }

    .btn:hover {
        background-color: #ff4500;
    }

    @media (max-width: 600px) {
        .btn {
            margin-left: 4.5rem;
        }
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

    <!-- เนื้อหาหลัก -->
    <div class="container-userinfo">
        <h2 style="text-align: center;">เพิ่มสินค้าใหม่</h2><br>
        <form action="add-product.php" method="POST" enctype="multipart/form-data">
            <div class="input-group">
                <label for="pname">ชื่อสินค้า:</label>
                <input type="text" name="pname" id="pname" required>
            </div>
            <div class="input-group">
                <label for="price">ราคา:</label>
                <input type="number" name="price" id="price" required>
            </div>
            <div class="input-group">
                <label for="amount">จำนวน:</label>
                <input type="number" name="amount" id="amount" required>
            </div>
            <div class="input-group">
                <label for="color">สี:</label>
                <input type="text" name="color" id="color" required>
            </div>
            <!-- Dropdown สำหรับเลือก Category -->
            <div class="input-group">
                <label for="category">หมวดหมู่สินค้า:</label>
                <select id="category" name="category" required>
                    <option value="">-- เลือกหมวดหมู่ --</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= $category['C_ID']; ?>">
                            <?= $category['C_Name']; ?>
                        </option>
                    <?php endforeach; ?>
                </select><br>
            </div>
            <div class="input-group">
                <label for="product_image">เลือกรูปภาพสินค้า:</label>
                <input type="file" id="product_image" name="product_image" required><br>
            </div>

            <div class="input-group">
                <label for="pname">รายละเอียดสินค้า:</label>
                <input type="textarea" name="detail" id="detail" required>
            </div>
            <button type="submit" class="btn">เพิ่มสินค้า</button>
        </form>
    </div>

    <footer>
        <div class="container">
            <div class="footer-links">
                <a href="#">เกี่ยวกับเรา</a>
                <a href="#">นโยบายความเป็นส่วนตัว</a>
                <a href="#">เงื่อนไขการใช้งาน</a>
                <a href="Contact-us/contact-us.html">ติดต่อเรา</a>
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

        <?php if (isset($_GET['status']) && $_GET['status'] == 'success'): ?>
            alert('เพิ่มสินค้าสำเร็จ!');
            // ลบ status=success จาก URL เพื่อไม่ให้แสดง alert ซ้ำเมื่อรีเฟรชหน้า
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.pathname);
            }
        <?php elseif (!empty($message)): ?>
            alert('<?= $message; ?>');
        <?php endif; ?>

    </script>

</body>

</html>
