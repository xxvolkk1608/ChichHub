<?php
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

    // สร้างที่อยู่จากข้อมูล
    $full_address = $province . ", " . $amphure . ", " . $tambon . ", " . $zipcode;

    // ตรวจสอบความถูกต้องของรหัสผ่าน
    if ($password !== $confirm_password) {
        echo "<script>alert('รหัสผ่านไม่ตรงกัน');</script>";
        exit;
    }

    // เข้ารหัสรหัสผ่าน
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // เพิ่มข้อมูลในตาราง Member

    //$insert_member = mysqli_query($conn, "INSERT INTO Member(Username, Password) VALUES('$email', '$hashed_password')");

    //$insert = mysqli_query($conn,"INSERT INTO Member(username, email, password, image, first_name, last_name, address, tel) VALUES('$name','$email','$pass','$image','$firstnn','$lastnn','$addre','$tte')") or die('query failed');
    $insert = mysqli_query($conn , "INSERT INTO Member(username, surname, email, phone, password, confirm-password,fulladd) VALUES('$name','$surname','$email','$phone','$password') ") or die('query failed');


    if ($insert_member) {
        // ดึง ID ของ Member ที่เพิ่งสร้าง
        $member_id = mysqli_insert_id($conn);

        // เพิ่มข้อมูลในตาราง Member_detail
        $insert_detail = mysqli_query($conn, "INSERT INTO Member_detail(ID, Name, Surname, Email, Tel, Address) VALUES('$member_id', '$name', '$surname', '$email', '$phone', '$full_address')");

        if ($insert_detail) {
            // ถ้าสมัครสำเร็จให้ทำการ redirect พร้อมแสดง Popup
            echo "<script>
                alert('สมัครสมาชิกสำเร็จ!');
                window.location.href='../Sign-In/signin.php';
                </script>";
        } else {
            echo "<script>alert('เกิดข้อผิดพลาดในการเพิ่มข้อมูลส่วนตัว');</script>";
        }
    } else {
        echo "<script>alert('เกิดข้อผิดพลาดในการสมัครสมาชิก');</script>";
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
                <form action="sign-up.php" method="POST" id="signup-form">
                    <div class="input-group">
                        <label for="name">ชื่อ</label>
                        <input type="text" name="Name" id="name" placeholder="กรอกชื่อของคุณ" required>
                    </div>
                    <div class="input-group">
                        <label for="surname">นามสกุล</label>
                        <input type="text" name="Surname" id="surname" placeholder="กรอกนามสกุลของคุณ" required>
                    </div>
                    <div class="input-group">
                        <label for="phone">เบอร์โทรศัพท์</label>
                        <input type="text" name="Phone" id="phone" placeholder="กรอกเบอร์โทรศัพท์ของคุณ" pattern="[0]{1}[0-9]{9}" maxlength="10" required>
                    </div>
                    <div class="input-group">
                        <label for="email">อีเมล</label>
                        <input type="email" name="Email" id="email" placeholder="กรอกอีเมลของคุณ" required pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$">
                    </div>
                    <div class="input-group">
                        <label for="password">รหัสผ่าน</label>
                        <div>
                            <input type="password" name="Password" id="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                            <button type="button" class="toggle-password" id="togglePassword">แสดงรหัสผ่าน</button>
                            <label style="color: red; font-size: 0.7em; font-weight: bold;">รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวพิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข และอักขระพิเศษ</label>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="confirm-password">ยืนยันรหัสผ่าน</label>
                        <div>
                            <input type="password" name="Confirm_Password" id="confirm-password" placeholder="ยืนยันรหัสผ่านของคุณ" required>
                            <button type="button" class="toggle-password" id="toggleConfirmPassword">แสดงรหัสผ่าน</button>
                        </div>
                    </div>
                    <div class="input-group">
                        <label for="province">จังหวัด</label>
                        <select name="Province" id="province" required>
                            <option value="" disabled selected>เลือกจังหวัด</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="amphure">อำเภอ/เขต</label>
                        <select name="Amphure" id="amphure">
                            <option value="" disabled selected>เลือกอำเภอ/เขต</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="tambon">ตำบล/แขวง</label>
                        <select name="Tambon" id="tambon">
                            <option value="" disabled selected>เลือกตำบล/แขวง</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <label for="zipcode">รหัสไปรษณีย์</label>
                        <select name="Zipcode" id="zipcode">
                            <option value="" disabled selected>เลือกรหัสไปรษณีย์</option>
                        </select>
                    </div>
                    <div class="input-group">
                        <button type="submit" name="submit" class="btn-signup">สมัครสมาชิก</button>
                        <script> console.log("hello")</script>
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

