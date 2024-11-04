<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Sign In</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
    <style>
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 70%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #007BFF;
            cursor: pointer;
            font-size: 14px;
            font-weight: bold;
            outline: none;
        }

        header {
            position: fixed;
            z-index: 999;
            background: var(--white);
            padding: 20px 0;
            border-bottom: 1px solid #ddd;
            width: 100%;
            display: flex;
            justify-content: center;
        }

        .sign-in-form {
            margin-top: 5%;
        }

        .alert {
            color: red;
            font-weight: bold;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="./signin.php">ChicHub</a></h1>
            </div>
        </div>
    </header>

    <div class="blur-background"></div>

    <section class="sign-in-section">
        <div class="sign-in-form">
            <h2>เข้าสู่ระบบ</h2>
            <form id="loginForm">
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
                <p id="alertMessage" class="alert"></p>
            </form>
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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
<<<<<<< HEAD
        document.querySelector("#togglePassword").addEventListener("click", function() {
=======
        document.querySelector("#togglePassword").addEventListener("click", function () {
>>>>>>> 562cd5e6502bbb3721f66c79b1f316cdea1ae35c
            const password = document.querySelector("#password");
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.textContent = type === "password" ? "แสดงรหัสผ่าน" : "ซ่อนรหัสผ่าน";
        });

        $(document).ready(function () {
            $("#loginForm").on("submit", function (e) {
                e.preventDefault(); // ป้องกันการรีเฟรชหน้า

                $.ajax({
                    type: "POST",
                    url: "check-login.php",
                    data: $(this).serialize(),
                    success: function (response) {
                        if (response.includes("เข้าสู่ระบบสำเร็จ")) {
                            window.location.href = "../Home/home.php";
                        } else {
                            $("#alertMessage").text(response); // แสดงข้อความในกรณีล็อกอินไม่สำเร็จ
                        }
                    },
                    error: function () {
                        $("#alertMessage").text("เกิดข้อผิดพลาดในการเชื่อมต่อ กรุณาลองใหม่อีกครั้ง");
                    }
                });
            });
        });
    </script>
</body>

</html>
