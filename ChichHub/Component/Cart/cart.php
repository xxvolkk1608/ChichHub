<?php
session_start();

// ตรวจสอบว่ามี session อยู่หรือไม่
if (!isset($_SESSION["Username"])) {
    // ถ้าไม่มี session ให้เช็คว่ามี cookies หรือไม่
    if (isset($_COOKIE["Username"])) {
        // ตั้งค่า session ใหม่จาก cookies
        $_SESSION["Username"] = $_COOKIE["Username"];
    }
}

// ถ้าไม่มีทั้ง session และ cookies ให้เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
if (!isset($_SESSION["Username"])) {
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

// แสดงชื่อผู้ใช้
$username = htmlspecialchars($_SESSION["Username"]);
// echo "สวัสดี, $username";
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ChicHub - Shopping Cart</title>
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


        .cart-summary {
            margin-top: 20px;
        }

        .cart-summary label {
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: #333;
            cursor: pointer;
            margin-top: 10px;
            user-select: none;
        }

        .cart-summary input[type="checkbox"] {
            display: none;
        }

        .cart-summary input[type="checkbox"]+span {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #FF5722;
            border-radius: 4px;
            margin-right: 10px;
            position: relative;
            transition: background-color 0.3s ease;
        }

        .cart-summary input[type="checkbox"]:checked+span {
            background-color: #FF5722;
        }

        .cart-summary input[type="checkbox"]:checked+span::before {
            content: "\f00c";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #fff;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 14px;
        }

        .final-price-container {
            margin-top: 10px;
            padding: 10px;
            background-color: #FFF3E0;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .final-price-container p {
            margin: 5px 0;
            font-size: 1.1em;
            color: #333;
        }

        .final-price {
            font-size: 1.4em;
            font-weight: bold;
            color: #FF5722;
        }

        .discount-amount {
            color: #388E3C;
            font-weight: bold;
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
                        <a href="#"><i class="fas fa-user"></i> สวัสดี,
                            <?php echo $username; ?>
                        </a>
                        <div class="dropdown-content">
                            <a href="../User/edit_profile.php">แก้ไขข้อมูลส่วนตัว</a>
                            <!-- ประวัติการสั่งซื้อ -->
                            <a href="../Order/order_history.php">ประวัติการสั่งซื้อ</a>
                            <?php if ($user['Role'] == 1): ?>
                                <!-- เฉพาะ Admin ที่มี Role = 1 -->
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

    <div class="blur-background"></div>

    <section class="cart-section">
        <div class="container">
            <h2 style="padding-top: 7rem; text-align:center">ตะกร้าสินค้าของคุณ</h2>
            <div class="cart-items" id="cart-items"></div>

            <div class="cart-summary">
                <h3>สรุปการสั่งซื้อ</h3>
                <p>ราคารวม: <span class="total-price" id="total-price">฿0</span></p>

                <!-- เพิ่มส่วนของช่องติ๊กเพื่อเลือกใช้โปรโมชั่น -->
                <label>
                    <input type="checkbox" id="promo-checkbox" />
                    <span></span> ใช้โปรโมชั่นลด 20% เมื่อซื้อเกิน 2,000 บาท
                </label>

                <!-- แสดงราคารวมหลังหักส่วนลดและจำนวนเงินที่ลดไป -->
                <div class="final-price-container">
                    <p>ราคาหลังหักส่วนลด: <span class="final-price" id="final-price">฿0</span></p>
                    <p>คุณประหยัดไป: <span class="discount-amount" id="discount-amount">฿0</span></p>
                </div>

                <button class="checkout-btn" id="checkout-btn">ชำระเงิน</button>
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
        const cartItemsContainer = document.getElementById('cart-items');
        const totalPriceElement = document.getElementById('total-price');
        const checkoutButton = document.getElementById('checkout-btn');
        const promoCheckbox = document.getElementById('promo-checkbox');
        const finalPriceElement = document.getElementById('final-price');
        const discountAmountElement = document.getElementById('discount-amount');

        // ดึงข้อมูลจาก LocalStorage
        const cartItems = JSON.parse(localStorage.getItem('cartItems')) || [];

        let totalPrice = 0;

        cartItems.forEach(item => {
            const itemElement = document.createElement('div');
            itemElement.classList.add('cart-item');
            itemElement.innerHTML = `
                <img src="${item.img}" alt="${item.name}" width="100" height="150">
                <div class="item-details">
                    <h3>${item.name}</h3>
                    <p>฿${item.price}</p>
                    <label>จำนวน:</label>
                    <input type="number" value="${item.quantity}" min="1" data-name="${item.name}">
                </div>
                <div class="remove-item">
                    <button class="remove-btn" data-name="${item.name}">ลบ</button>
                </div>
            `;
            cartItemsContainer.appendChild(itemElement);

            totalPrice += parseFloat(item.price) * item.quantity;
        });

        totalPriceElement.textContent = `฿${totalPrice.toFixed(2)}`;
        updateFinalPrice(totalPrice); // คำนวณส่วนลดครั้งแรก

        // ฟังก์ชันคำนวณราคารวมหลังหักส่วนลด
        function updateFinalPrice(totalPrice) {
            let finalPrice = totalPrice;
            let discountAmount = 0;

            if (promoCheckbox.checked && totalPrice >= 2000) {
                discountAmount = totalPrice * 0.2; // ลด 20%
                finalPrice = totalPrice - discountAmount;
            }

            finalPriceElement.textContent = `฿${finalPrice.toFixed(2)}`;
            discountAmountElement.textContent = `฿${discountAmount.toFixed(2)}`;
            localStorage.setItem('finalPrice', finalPrice); // เก็บราคาสุดท้ายใน LocalStorage
        }

        // การอัปเดตจำนวนสินค้าและคำนวณราคารวมใหม่
        document.querySelectorAll('input[type="number"]').forEach(input => {
            input.addEventListener('change', function () {
                const newQuantity = parseInt(this.value, 10);
                const productName = this.dataset.name;

                cartItems.forEach(item => {
                    if (item.name === productName) {
                        item.quantity = newQuantity;
                    }
                });

                // อัปเดตข้อมูลใน LocalStorage
                localStorage.setItem('cartItems', JSON.stringify(cartItems));

                // คำนวณราคารวมใหม่
                let totalPrice = 0;
                cartItems.forEach(item => {
                    const itemPrice = parseFloat(item.price);
                    totalPrice += itemPrice * item.quantity;
                });

                totalPriceElement.textContent = `฿${totalPrice.toFixed(2)}`;
                updateFinalPrice(totalPrice); // คำนวณส่วนลดใหม่
            });
        });

        // เรียกใช้ updateFinalPrice เมื่อมีการเปลี่ยนแปลงช่องติ๊กส่วนลด
        promoCheckbox.addEventListener('change', () => updateFinalPrice(totalPrice));

        // การลบสินค้า
        const removeButtons = document.querySelectorAll('.remove-btn');
        removeButtons.forEach(button => {
            button.addEventListener('click', () => {
                const productName = button.dataset.name;
                const updatedCartItems = cartItems.filter(item => item.name !== productName);
                localStorage.setItem('cartItems', JSON.stringify(updatedCartItems));
                window.location.reload(); // รีเฟรชหน้า
            });
        });

        // ฟังก์ชันสำหรับชำระเงิน
        checkoutButton.addEventListener('click', () => {
            if (cartItems.length === 0) {
                alert("ตะกร้าของคุณว่างเปล่า");
                return;
            }

            // ตรวจสอบความถูกต้องของข้อมูลก่อนส่ง
            const validCartItems = cartItems.every(item => item.id && item.quantity);

            if (!validCartItems) {
                alert("ข้อมูลสินค้าบางอย่างไม่ครบถ้วนในตะกร้า กรุณาลองใหม่อีกครั้ง");
                return;
            }

            // ส่งข้อมูลตะกร้าสินค้าไปยังเซิร์ฟเวอร์เพื่อสร้างคำสั่งซื้อ
            fetch('process-checkout.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(cartItems),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // ล้างข้อมูลตะกร้าสินค้าใน Local Storage
                        localStorage.removeItem('cartItems');

                        // เปลี่ยนเส้นทางไปยังหน้า pay.php พร้อม order_id
                        window.location.href = `../Pay/pay.php?Ord_id=${data.Ord_id}`;
                    } else {
                        alert(`เกิดข้อผิดพลาดในการสร้างคำสั่งซื้อ: ${data.message}`);
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('เกิดข้อผิดพลาด');
                });
        });

    </script>

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
                window.location.href = "./logout.php";
            }
        }
    </script>
</body>

</html>