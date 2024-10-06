<?php
// Enable error reporting to show detailed errors
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../db_connection.php';

if (isset($_POST['submit'])) {
    // รับข้อมูลจากฟอร์ม
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $id = mysqli_real_escape_string($conn, $_POST['md_id']);  // รับค่า MD_ID
   

    // เข้ารหัสรหัสผ่านก่อนบันทึก (คุณอาจต้องเพิ่มฟังก์ชันเข้ารหัสรหัสผ่าน เช่น password_hash())
    
    // บันทึกข้อมูลลงฐานข้อมูล (รวมฟิลด์ MD_ID ด้วย)
    $query = "INSERT INTO Member (ID, Username, Password, MD_ID,test) VALUES ('$id', '$username', '$password', '$id')";

    // Print the query for debugging
    echo $query;
    
    $result = mysqli_query($conn, $query);

    if ($result) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ');</script>";
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการบันทึกข้อมูล');</script>";
    }
}
?>



<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Sign Up</title>
</head>

<body>
    <header>
        <div class="container-header">
            <!-- คุณสามารถใส่ header ได้ตามต้องการ -->
        </div>
    </header>

    <section class="sign-up-section">
        <div class="container">
            <div class="sign-up-form">
                <h2>สมัครสมาชิก</h2>
                <form action="" method="POST" id="signup-form">
                    <div class="input-group">
                        <label for="username">ชื่อผู้ใช้</label>
                        <input type="text" name="username" id="username" placeholder="กรอกชื่อผู้ใช้" required>
                    </div>
                   
                    <div class="input-group">
                        <label for="password">รหัสผ่าน</label>
                        <div>
                            <input type="password" name="password" id="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                        </div>
                    </div>

                    <div class="input-group">
                        <label for="md_id">MD_ID</label>
                        <input type="number" name="md_id" id="md_id" placeholder="กรอก MD_ID" required>
                    </div>

                    <div class="input-group">
                        <button type="submit" name="submit" class="btn-signup">สมัครสมาชิก</button>
                    </div>
                    
                    <p class="sign-in-link">มีบัญชีอยู่แล้ว? <a href="">เข้าสู่ระบบ</a></p>
                </form>
            </div>
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
            <p>&copy; 2024 ChicHub. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>
</body>

</html>
