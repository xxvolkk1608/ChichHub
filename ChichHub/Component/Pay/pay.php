<?php


session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามี session ผู้ใช้อยู่หรือไม่
if (!isset($_SESSION["Username"])) {
    die("กรุณาเข้าสู่ระบบ");
}

// รับ Ord_detail_id จาก URL
$Ord_id = $_GET['Ord_id'] ?? null;

if ($Ord_id) {
    // ดึงข้อมูลคำสั่งซื้อจากฐานข้อมูล ตรวจสอบให้แน่ใจว่าใช้ชื่อคอลัมน์ที่ถูกต้องในคำสั่ง SQL
    $stmt = $pdo->prepare("SELECT * FROM Ord_detail WHERE Ord_id = ?");
    $stmt->execute([$Ord_id]);
    $Ord_detail = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($Ord_detail) {
        //echo "<h2>รายการสั่งซื้อของคุณ</h2>";
        foreach ($Ord_detail as $Ord_detail) {
            //echo "<p>สินค้า: {$Ord_detail['P_ID']} - จำนวน: {$Ord_detail['Amount']} - สถานะการชำระเงิน: {$Ord_detail['Payment_status']}</p>";
        }
    } else {
        echo "<p>ไม่พบคำสั่งซื้อ</p>";
    }
} else {
    echo "<p>ไม่พบคำสั่งซื้อ</p>";
}

// แสดงชื่อผู้ใช้
$username = htmlspecialchars($_SESSION["Username"]);

?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Payment</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../styles/styles.css">
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

        /* Dropdown Styles */
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

        .dropdown:hover .dropdown-content {
            display: block;
        }
        /* สไตล์การชำระเงิน */
        .payment-container {
            max-width: 600px;
            margin: 10% auto;
            padding: 30px;
            border-radius: 8px;
            background-color: #fff;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
            border-top: 5px solid #ff5722;
        }

        .payment-method, .extra-fields {
            margin-top: 20px;
        }

        select[name="payment_method"] {
            padding: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            background-color: #f9f9f9;
            color: #333;
            width: 100%;
        }

        .extra-fields input {
            padding: 10px;
            margin-top: 10px;
            font-size: 16px;
            border-radius: 5px;
            border: 1px solid #ddd;
            width: 100%;
        }

        .pay-button {
            margin-top: 20px;
            padding: 12px;
            background-color: #ff5722;
            color: white;
            font-size: 18px;
            cursor: pointer;
            border-radius: 5px;
            border: none;
            text-align: center;
            transition: background-color 0.3s;
            width: 100%;
        }

        .pay-button:hover {
            background-color: #e64a19;
        }
    </style>
</head>

<body>
    <header>
        <div class="container-header">
            <div class="logo">
                <h1 class="chic-hub"><a href="../Home/home.php">ChicHub</a></h1>
            </div>
            <nav>
                <ul class="nav-links">
                    <li><a href="../Home/home.php">หน้าหลัก</a></li>
                    <li><a href="../Shop/shop.php">ร้านค้า</a></li>
                    <li><a href="../Category/Promotion.php">โปรโมชั่น</a></li>
                    <li><a href="../Contact-us/contact-us.php">ติดต่อเรา</a></li>
                    <li class="dropdown">
                        <a href="#"><i class="fas fa-user"></i> สวัสดี, <?php echo $username; ?></a>
                        <div class="dropdown-content">
                            <a href="../User/edit_profile.php">แก้ไขข้อมูลส่วนตัว</a>
                            <!-- ประวัติการสั่งซื้อ -->
                            <a href="../Order/order_history.php">ประวัติการสั่งซื้อ</a>
                            <?php if ($user['Role'] == 1): ?> <!-- เฉพาะ Admin ที่มี Role = 1 -->
                                <a href="../Admin/add-product.php">เพิ่มสินค้า</a>
                            <?php endif; ?>
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

    <div class="payment-container">
        <h2>เลือกวิธีการชำระเงิน</h2>
        <form method="POST" action="process_payment.php" id="payment-form">
            <input type="hidden" name="Ord_id" value="<?php echo $Ord_id; ?>">

            <div class="payment-method">
                <label for="payment_method">เลือกวิธีการชำระเงิน:</label>
                <select name="payment_method" id="payment_method">
                    <option value="">-- เลือกวิธีการชำระเงิน --</option>
                    <option value="cash">เงินสด</option>
                    <option value="credit_card">บัตรเครดิต</option>
                    <option value="mobile_banking">Mobile Banking</option>
                </select>
            </div>

            <!-- ช่องข้อมูลเพิ่มเติม -->
            <div class="extra-fields" id="extra-fields" style="display: none;">
                <input type="text" name="credit_card_number" id="credit_card_number" placeholder="หมายเลขบัตรเครดิต" maxlength="19" style="display: none;">
                <input type="text" name="expiry_date" id="expiry_date" placeholder="MM/YY" maxlength="5" style="display: none;">
                <input type="text" name="cvv" id="cvv" placeholder="CVV" maxlength="3" style="display: none;">
                <input type="text" name="mobile_banking_number" id="mobile_banking_number" placeholder="เบอร์โทร Mobile Banking" maxlength="10" style="display: none;">
            </div>

            <button type="submit" class="pay-button">ยืนยันการชำระเงิน</button>
        </form>
    </div>

    <script>
        const paymentMethodSelect = document.getElementById("payment_method");
        const extraFieldsContainer = document.getElementById("extra-fields");
        const creditCardInput = document.getElementById("credit_card_number");
        const expiryDateInput = document.getElementById("expiry_date");
        const cvvInput = document.getElementById("cvv");
        const mobileBankingInput = document.getElementById("mobile_banking_number");

        paymentMethodSelect.addEventListener("change", function () {
            // ซ่อนช่องข้อมูลเสริมทั้งหมดก่อน
            creditCardInput.style.display = "none";
            expiryDateInput.style.display = "none";
            cvvInput.style.display = "none";
            mobileBankingInput.style.display = "none";
            extraFieldsContainer.style.display = "none";

            // แสดงช่องข้อมูลตามวิธีการชำระเงินที่เลือก
            if (this.value === "credit_card") {
                creditCardInput.style.display = "block";
                expiryDateInput.style.display = "inline-block";
                cvvInput.style.display = "inline-block";
                extraFieldsContainer.style.display = "block";
            } else if (this.value === "mobile_banking") {
                mobileBankingInput.style.display = "block";
                extraFieldsContainer.style.display = "block";
            }
        });

        // จัดรูปแบบหมายเลขบัตรเครดิตให้เป็นกลุ่มละ 4 หลัก
        creditCardInput.addEventListener("input", function () {
            let cardNumber = creditCardInput.value.replace(/\D/g, ''); // เอาตัวอักษรที่ไม่ใช่ตัวเลขออก
            if (cardNumber.length > 16) {
                cardNumber = cardNumber.slice(0, 16); // จำกัดไม่เกิน 16 หลัก
            }
            creditCardInput.value = cardNumber.replace(/(\d{4})(?=\d)/g, '$1 '); // แบ่งเป็นกลุ่มละ 4 หลัก
        });

        // จัดรูปแบบวันหมดอายุให้เป็น MM/YY
        expiryDateInput.addEventListener("input", function () {
            let dateValue = expiryDateInput.value.replace(/\D/g, ''); // เอาตัวอักษรที่ไม่ใช่ตัวเลขออก
            if (dateValue.length > 4) {
                dateValue = dateValue.slice(0, 4); // จำกัดไม่เกิน 4 หลัก
            }
            if (dateValue.length > 2) {
                dateValue = dateValue.slice(0, 2) + '/' + dateValue.slice(2);
            }
            expiryDateInput.value = dateValue;
        });
    </script>

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
    const paymentForm = document.getElementById('payment-form');
    const paymentMethodSelect = document.getElementById("payment_method");
    
    paymentForm.addEventListener('submit', function (e) {
        e.preventDefault(); // ป้องกันการรีเฟรชหน้า

        // รับค่าการเลือกวิธีการชำระเงิน
        const paymentMethod = paymentMethodSelect.value;

        if (paymentMethod === "credit_card") {
            const creditCardNumber = document.getElementById("credit_card_number").value;
            const expiryDate = document.getElementById("expiry_date").value;
            const cvv = document.getElementById("cvv").value;
            
            // ตรวจสอบว่ากรอกข้อมูลครบ
            if (!creditCardNumber || !expiryDate || !cvv) {
                alert("กรุณากรอกข้อมูลบัตรเครดิตให้ครบ");
                return;
            }
        } else if (paymentMethod === "mobile_banking") {
            const mobileBankingNumber = document.getElementById("mobile_banking_number").value;
            if (!mobileBankingNumber) {
                alert("กรุณากรอกหมายเลขโทรศัพท์ Mobile Banking");
                return;
            }
        }

        // ส่งข้อมูลไปยัง process_payment.php
        fetch('process_payment.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                Ord_id: "<?php echo $Ord_id; ?>",
                payment_method: paymentMethod,
                credit_card_number: document.getElementById("credit_card_number").value,
                expiry_date: document.getElementById("expiry_date").value,
                cvv: document.getElementById("cvv").value,
                mobile_banking_number: document.getElementById("mobile_banking_number").value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('การชำระเงินสำเร็จ');
                localStorage.removeItem('cartItems'); // ล้างตะกร้าหลังจากการชำระเงินสำเร็จเท่านั้น
                window.location.href = 'thankyou.php'; // ไปยังหน้าขอบคุณ
            } else {
                alert('เกิดข้อผิดพลาดในการชำระเงิน');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('เกิดข้อผิดพลาด');
        });
    });
</script>

</body>

</html>