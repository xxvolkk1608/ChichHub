<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include '../db_connection.php';

if (isset($_POST['submit'])) {
    // แยกชื่อและนามสกุลออกจาก fullname
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $surname = mysqli_real_escape_string($conn, $_POST['surname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']); 
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $confirm_password = mysqli_real_escape_string($conn, $_POST['confirm-password']);

    // ดึงข้อมูลจังหวัด อำเภอ ตำบล และรหัสไปรษณีย์
    $province = mysqli_real_escape_string($conn, $_POST['province']);
    $amphure = mysqli_real_escape_string($conn, $_POST['amphure']);
    $tambon = mysqli_real_escape_string($conn, $_POST['tambon']);
    $zipcode = mysqli_real_escape_string($conn, $_POST['zipcode']);

    // ตรวจสอบความถูกต้องของรหัสผ่าน
    if ($password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน');</script>";
        exit;
    }

    // เพิ่มข้อมูลในตาราง Member โดยไม่ต้องระบุค่า ID
    $query = "INSERT INTO Member (Name, Surname, Phone, Password, Province, Amphure, Tambon, Zipcode, Gmail) 
              VALUES ('$name', '$surname', '$phone', '$password', '$province', '$amphure', '$tambon', '$zipcode' , '$email' )";



    if (mysqli_query($conn, $query)) {
        echo "<script>alert('สมัครสมาชิกสำเร็จ');</script>";
    } else {
        // แสดงข้อความข้อผิดพลาดหากการบันทึกข้อมูลล้มเหลว
        echo "<script>alert('เกิดข้อผิดพลาด: " . mysqli_error($conn) . "');</script>";
    }
}

?>




<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Sign Up</title>
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ลิงก์ไปยังไฟล์ CSS -->
    <link rel="stylesheet" href="../styles.css">
     <!-- JSON ที่อยู่ -->
    <script src="../script.js"></script>
</head>

<body>
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="../Sign-In/index.php">ChicHub</a></h1>
            </div>
        </div>
    </header>

    <section class="sign-up-section">
        <div class="container">
            <div class="sign-up-form">
                <h2>สมัครสมาชิก</h2>
                <form action=" " method="POST" id="signup-form">
                    <div class="input-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" name="name" id="name" placeholder="กรอกชื่อของคุณ" required>
                    </div>
                    <div class="input-group">
                        <label for="surname">นามสกุล</label>
                        <input type="text" name="surname" id="surname" placeholder="กรอกนามสกุลของคุณ" required>
                    </div>
                    <div class="input-group">
                        <label for="phone">เบอร์โทรศัพท์</label>
                        <input type="text" name="phone" id="phone" placeholder="กรอกเบอร์โทรศัพท์ของคุณ" pattern="[0]{1}[0-9]{9}" maxlength="10" required>
                    </div>
                    <div class="input-group">
                        <label for="email">อีเมล</label>
                        <input type="email" name="email" id="email" placeholder="กรอกอีเมลของคุณ" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
                    </div>
                    <div class="input-group">
                        <label for="password">รหัสผ่าน</label>
                        <div>
                            <input type="password" name="password" id="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                            <button type="button" class="toggle-password" id="togglePassword">แสดงรหัสผ่าน</button>
                            <label style="color: red; font-size: 0.7em; font-weight: bold;">รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวพิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข และอักขระพิเศษ</label>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="confirm-password">ยืนยันรหัสผ่าน</label>
                        <div>
                            <input type="password" name="confirm-password" id="confirm-password" placeholder="ยืนยันรหัสผ่านของคุณ" required>
                            <button type="button" class="toggle-password" id="toggleConfirmPassword">แสดงรหัสผ่าน</button>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="province">จังหวัด</label>
                        <select name="province" id="province" required>
                            <option value="" disabled selected>เลือกจังหวัด</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="amphure">อำเภอ/เขต</label>
                        <select name="amphure" id="amphure">
                            <option value="" disabled selected>เลือกอำเภอ/เขต</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="tambon">ตำบล/แขวง</label>
                        <select name="tambon" id="tambon">
                            <option value="" disabled selected>เลือกตำบล/แขวง</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="zipcode">รหัสไปรษณีย์</label>
                        <select name="zipcode" id="zipcode">
                            <option value="" disabled selected>เลือกรหัสไปรษณีย์</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <button type="submit" name="submit" class="btn-signup">สมัครสมาชิก</button>
                        
                    </div>
                    <p class="sign-in-link">มีบัญชีอยู่แล้ว? <a href="../Sign-In/signin.php">เข้าสู่ระบบ</a></p>
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
            <div class="social-media">
                <a href="#"><i class="fab fa-facebook-f"></i></a>
                <a href="#"><i class="fab fa-instagram"></i></a>
                <a href="#"><i class="fab fa-twitter"></i></a>
            </div>
            <p>&copy; 2024 ChicHub. สงวนลิขสิทธิ์.</p>
        </div>
    </footer>

    <script>
        
        document.querySelector("form").addEventListener("submit", function(event) {
            const email = document.getElementById("email").value;
            const password = document.getElementById("password").value;
            const confirmPassword = document.getElementById("confirm-password").value;

            // ตรวจสอบอีเมล
            const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
            if (!emailRegex.test(email)) {
                alert("อีเมลไม่ถูกต้อง");
                event.preventDefault();
                return;
            }

            // ตรวจสอบรูปแบบรหัสผ่าน
            const passwordRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/;
            if (!passwordRegex.test(password)) {
                alert("รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวพิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข และอักขระพิเศษ");
                event.preventDefault();
                return;
            }

            // ตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
            if (password !== confirmPassword) {
                alert("รหัสผ่านไม่ตรงกัน");
                event.preventDefault();
                return;
            }
        });

        document.querySelector("#togglePassword").addEventListener("click", function() {
            const password = document.querySelector("#password");
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.textContent = type === "password" ? "แสดงรหัสผ่าน" : "ซ่อนรหัสผ่าน";
        });

        document.querySelector("#toggleConfirmPassword").addEventListener("click", function() {
            const confirmPassword = document.querySelector("#confirm-password");
            const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
            confirmPassword.setAttribute("type", type);
            this.textContent = type === "password" ? "แสดงรหัสผ่าน" : "ซ่อนรหัสผ่าน";
        });
    </script>
</body>
</html>

