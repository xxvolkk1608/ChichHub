<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Sign In</title>
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ลิงก์ไปยังไฟล์ CSS -->
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .toggle-password {
            position: absolute;
            right: 10px;
            /* ชิดขอบขวา */
            top: 70%;
            /* ตำแหน่งกึ่งกลางแนวดิ่ง */
            transform: translateY(-50%);
            /* ปรับตำแหน่งให้อยู่กึ่งกลางแนวดิ่ง */
            background: none;
            border: none;
            color: #007BFF;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            outline: none;
        }

        .toggle-password:focus {
            outline: none;
            /* เอา outline ออกเมื่อคลิก */
        }
    </style>
</head>

<body>
    <!-- ส่วนหัว (Header) -->
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="./signin.php">ChicHub</a></h1>
            </div>
            </nav>
        </div>
    </header>

    <!-- Blur Background -->
    <div class="blur-background"></div>

    <!-- ส่วนของการเข้าสู่ระบบ -->
    <section class="sign-in-section">
        <div class="sign-in-form">
            <h2>เข้าสู่ระบบ</h2>
            <form action="check-login.php" method="post">
                <div class="input-group">
                    <label for="Username">Username</label>
                    <input type="text" name="Username" id="Username" placeholder="กรอก Username ของคุณ" required>
                </div>
                <div class="input-group">
                    <label for="password">รหัสผ่าน</label>
                    <input type="password" name="Password" id="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                    <button type="button" class="toggle-password" id="togglePassword">แสดงรหัสผ่าน</button>
                </div>
                <button type="submit" class="btn-signin">เข้าสู่ระบบ</button>
                <p class="sign-up-link">ยังไม่มีบัญชี? <a href="../Sign-Up/signup.php">สมัครสมาชิก</a></p>
            </form>
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

        document.querySelector("#togglePassword").addEventListener("click", function () {
            const password = document.querySelector("#password");
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.textContent = type === "password" ? "แสดงรหัสผ่าน" : "ซ่อนรหัสผ่าน";
        });

    </script>
</body>

</html>