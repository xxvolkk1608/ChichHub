<?php
session_start(); // Start a session for user authentication

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    include '../db_connection.php'; // Include your database connection

    $Username = $_POST['Usernamel'];
    $Password = $_POST['Password'];

    // Check if user exists in the database
    $sql = "SELECT * FROM Member WHERE Username='$Username'";
    $result = mysqli_query($conn, $sql);

    if (mysqli_num_rows($result) > 0) {
        $user = mysqli_fetch_assoc($result);
        // Verify password
        if (password_verify($password, $User['Password'])) {
            $_SESSION['user_id'] = $user['member_id'];
            header('Location: ../Home/home.html'); // Redirect to homepage after successful login
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No user found with that email.";
    }

    mysqli_close($conn);
}
?>


<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Sign In</title>
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ลิงก์ไปยังไฟล์ CSS -->
    <link rel="stylesheet" href="../styles.css">
</head>
<body>

    <!-- ส่วนหัว (Header) -->
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="#">ChicHub</a></h1>
            </div>
        </div>
    </header>

    <!-- Blur Background -->
    <div class="blur-background"></div>

    <!-- ส่วนของการเข้าสู่ระบบ -->
    <section class="sign-in-section">
        <div class="container">
            <div class="sign-in-form">
                <h2>เข้าสู่ระบบ</h2>
                <form action="#">
                    <div class="input-group">
                        <label for="email">อีเมล</label>
                        <input type="email" id="email" placeholder="กรอกอีเมลของคุณ" required>
                    </div>
                    <div class="input-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" id="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                    </div>
                    <div class="input-group">
                        <button type="submit" class="btn-signin">เข้าสู่ระบบ</button>
                    </div>
                    <p class="sign-up-link">ยังไม่มีบัญชี? <a href="../Sign-Up/signup.php">สมัครสมาชิก</a></p>
                </form>
            </div>
        </div>
    </section>

    <!-- ฟุตเตอร์ (Footer) -->
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
            blurBackground.classList.toggle('active'); // เบลอพื้นหลังเมื่อเมนูเปิด
        });

        // ปิดเมนูเมื่อคลิกที่เบลอพื้นหลัง
        blurBackground.addEventListener('click', () => {
            navLinks.classList.remove('active');
            blurBackground.classList.remove('active');
        });
    </script>
</body>

</html>