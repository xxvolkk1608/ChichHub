<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Sign Up</title>
    <!-- ลิงก์ไปยัง Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- ลิงก์ไปยังไฟล์ CSS -->
    <link rel="stylesheet" href="../styles/styles.css">
    <script src="script.js"></script>

    <style>
        .toggle-password {
            position: absolute;
            right: 10px;
            /* ชิดขอบขวา */
            top: 47%;
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

        .toggle-password2 {
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

        .toggle-password2:focus {
            outline: none;
            /* เอา outline ออกเมื่อคลิก */
        }
    </style>
</head>

<body>
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="../Sign-In/signin.php">ChicHub</a></h1>
            </div>
        </div>
    </header>

    <section class="sign-up-section">
        <div class="container">
            <div class="sign-up-form">
                <h2>สมัครสมาชิก</h2>
                <form action="process-signup.php" method="POST" id="signup-form">
                    <div class="input-group">
                        <label for="username">Username</label>
                        <input type="text" name="username" id="username" placeholder="กรอก Username ของคุณ" required>
                        <!-- ปุ่มสำหรับตรวจสอบ Username -->
                        <button type="button" id="check-username-btn">ตรวจสอบ Username</button>
                        <!-- แสดงผลการตรวจสอบ -->
                        <div id="username-feedback" style="font-size: 0.9em; color: red;"></div>
                    </div>

                    <!-- ฟิลด์สำหรับรหัสผ่าน -->
                    <div class="input-group">
                        <label for="password">รหัสผ่าน</label>
                        <input type="password" name="password" id="password" placeholder="กรอกรหัสผ่านของคุณ" required>
                        <button type="button" class="toggle-password" id="togglePassword">แสดงรหัสผ่าน</button>
                        <label style="color: red; font-size: 0.7em; font-weight: bold;">
                            รหัสผ่านต้องมีอย่างน้อย 8 ตัวอักษร ประกอบด้วยตัวพิมพ์ใหญ่ พิมพ์เล็ก ตัวเลข และอักขระพิเศษ
                        </label>
                    </div>

                    <!-- ฟิลด์สำหรับยืนยันรหัสผ่าน -->
                    <div class="input-group">
                        <label for="confirm-password">ยืนยันรหัสผ่าน</label>
                        <input type="password" name="confirm-password" id="confirm-password"
                            placeholder="ยืนยันรหัสผ่านของคุณ" required>
                        <button type="button" class="toggle-password2" id="toggleConfirmPassword">แสดงรหัสผ่าน</button>
                    </div>

                    <!-- ฟิลด์ข้อมูลผู้ใช้ -->
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
                        <input type="text" name="phone" id="phone" placeholder="กรอกเบอร์โทรศัพท์ของคุณ"
                            pattern="[0]{1}[0-9]{9}" maxlength="10" required>
                    </div>
                    <div class="input-group">
                        <label for="email">อีเมล</label>
                        <input type="email" name="email" id="email" pattern="[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" placeholder="กรอกอีเมลของคุณ" required>
                    </div>

                    <!-- Dropdown จังหวัด -->
                    <div class="input-group">
                        <label for="province">จังหวัด</label>
                        <select id="province" name="province" required>
                            <option value="" disabled selected>เลือกจังหวัด</option>
                        </select>
                    </div>

                    <!-- Dropdown อำเภอ -->
                    <div class="input-group">
                        <label for="amphure">อำเภอ</label>
                        <select id="amphure" name="amphure" required disabled>
                            <option value="" disabled selected>เลือกอำเภอ</option>
                        </select>
                    </div>

                    <!-- Dropdown ตำบล -->
                    <div class="input-group">
                        <label for="tambon">ตำบล</label>
                        <select id="tambon" name="tambon" required disabled>
                            <option value="" disabled selected>เลือกตำบล</option>
                        </select>
                    </div>

                    <!-- Dropdown รหัสไปรษณีย์ -->
                    <div class="input-group">
                        <label for="zipcode">รหัสไปรษณีย์</label>
                        <select id="zipcode" name="zipcode" required disabled>
                            <option value="" disabled selected>เลือกรหัสไปรษณีย์</option>
                        </select>
                    </div>

                    <!-- ฟิลด์แสดงข้อมูล Address -->
                    <div class="input-group">
                        <label for="address">ที่อยู่</label>
                        <input type="text" id="address" name="address" readonly>
                    </div>

                    <div class="input-group">
                        <button type="submit" name="submit" class="btn-signup">สมัครสมาชิก</button>
                    </div>
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

    <!-- ใส่ไฟล์ script.js -->
    <script src="script.js"></script>
    <script>
        // แสดงและซ่อนรหัสผ่าน
        document.querySelector("#togglePassword").addEventListener("click", function () {
            const password = document.querySelector("#password");
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);
            this.textContent = type === "password" ? "แสดงรหัสผ่าน" : "ซ่อนรหัสผ่าน";
        });

        document.querySelector("#toggleConfirmPassword").addEventListener("click", function () {
            const confirmPassword = document.querySelector("#confirm-password");
            const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
            confirmPassword.setAttribute("type", type);
            this.textContent = type === "password" ? "แสดงรหัสผ่าน" : "ซ่อนรหัสผ่าน";
        });
    </script>

    <script>
        // ฟังก์ชันรวบรวมข้อมูลจาก dropdowns และอัปเดตฟิลด์ที่อยู่
        function updateAddress() {
            const province = document.getElementById('province').value || '';
            const amphure = document.getElementById('amphure').value || '';
            const tambon = document.getElementById('tambon').value || '';
            const zipcode = document.getElementById('zipcode').value || '';

            // รวมข้อมูลทั้งหมดลงในฟิลด์ address
            const fullAddress = `จังหวัด: ${province}, อำเภอ: ${amphure}, ตำบล: ${tambon}, รหัสไปรษณีย์: ${zipcode}`;
            document.getElementById('address').value = fullAddress;
        }

        // Event listeners สำหรับ dropdowns ทุกครั้งที่มีการเปลี่ยนแปลง
        document.getElementById('province').addEventListener('change', updateAddress);
        document.getElementById('amphure').addEventListener('change', updateAddress);
        document.getElementById('tambon').addEventListener('change', updateAddress);
        document.getElementById('zipcode').addEventListener('change', updateAddress);
    </script>
    <script>
        // ฟังก์ชันตรวจสอบ Username เมื่อคลิกปุ่ม
        document.getElementById('check-username-btn').addEventListener('click', function () {
            let username = document.getElementById('username').value; // ดึงค่าจาก input

            // ตรวจสอบว่าได้กรอก Username หรือยัง
            if (username === '') {
                document.getElementById('username-feedback').textContent = 'กรุณากรอก Username ก่อนตรวจสอบ';
                return; // หยุดการทำงานหากยังไม่ได้กรอก Username
            }

            // สร้าง AJAX Request
            let xhr = new XMLHttpRequest();
            xhr.open('POST', 'check-username.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

            // ตรวจสอบการทำงานและแสดงผลลัพธ์ใน console
            xhr.onload = function () {
                if (xhr.status === 200) {
                    console.log(xhr.responseText); // แสดงผลลัพธ์ใน console เพื่อดูข้อมูลที่ได้รับ

                    let response = xhr.responseText;
                    let usernameFeedback = document.getElementById('username-feedback');

                    // ถ้า Username ซ้ำ แสดงข้อความแจ้งเตือน
                    if (response === 'taken') {
                        usernameFeedback.textContent = 'Username นี้ถูกใช้แล้ว กรุณาเปลี่ยน';
                        usernameFeedback.style.color = 'red';
                    } else {
                        usernameFeedback.textContent = 'Username นี้สามารถใช้ได้';
                        usernameFeedback.style.color = 'green';
                    }
                } else {
                    console.error('Error:', xhr.status, xhr.statusText); // แสดง error ใน console
                }
            };

            // ตรวจสอบข้อผิดพลาด
            xhr.onerror = function () {
                console.error('Request failed');
            };

            xhr.send('Username=' + encodeURIComponent(username)); // ตรวจสอบว่าข้อมูลถูกส่งถูกต้อง
        });

    </script>
</body>

</html>