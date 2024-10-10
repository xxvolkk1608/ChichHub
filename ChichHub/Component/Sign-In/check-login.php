<?php
session_start();
include 'connect.php';  // เชื่อมต่อกับฐานข้อมูล

// ตรวจสอบว่ามีการส่ง username และ password มาหรือไม่
if (isset($_POST["Username"]) && isset($_POST["Password"])) {
    // เตรียมคำสั่ง SQL เพื่อค้นหาข้อมูลผู้ใช้
    $stmt = $pdo->prepare("SELECT * FROM Member WHERE Username = ?");
    $stmt->execute([$_POST["Username"]]);

    // ดึงข้อมูลของผู้ใช้จากฐานข้อมูล
    $row = $stmt->fetch();

    // ตรวจสอบว่าพบ Username และตรวจสอบว่ารหัสผ่านตรงกันหรือไม่
    if ($row && $_POST["Password"] === $row["Password"]) {
        // รหัสผ่านถูกต้อง
        session_regenerate_id(); // ป้องกัน session fixation attack
        $_SESSION["Username"] = $row["Username"]; // เก็บข้อมูลใน session

        // ตั้งค่า cookies โดยอัตโนมัติให้จำ Username เป็นเวลา 7 วัน (7*24*60*60 วินาที)
        setcookie("Username", $row["Username"], time() + (7 * 24 * 60 * 60), "/");

        // เปลี่ยนเส้นทางไปยังหน้า home
        header("Location: ../Home/home.php");
        exit();
    } else {
        // หาก username หรือ password ไม่ถูกต้อง
        header("Location: failedlogin.php");
    }
} else {
    // กรณีที่ยังไม่ได้กรอก username หรือ password
    echo "กรุณากรอก Username และ Password";
}
?>
