<?php
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

if (isset($_POST['Username'])) {
    $username = $_POST['Username'];

    // ตรวจสอบว่าเชื่อมต่อกับฐานข้อมูลสำเร็จหรือไม่
    if (!$conn) {
        echo 'Error: Database connection failed.';
        exit();
    }

    // คำสั่ง SQL เพื่อตรวจสอบ username
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Member WHERE Username = ?");
    $stmt->execute([$username]);
    $count = $stmt->fetchColumn();

    if ($count > 0) {
        echo 'taken';  // ถ้า username ซ้ำ
    } else {
        echo 'available';  // ถ้า username ใช้ได้
    }
} else {
    echo 'No Username provided';  // ถ้าไม่มี username ส่งมา
}
?>
