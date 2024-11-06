<?php
session_start();
include 'connect.php';  // เชื่อมต่อกับฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["Username"]) && isset($_POST["Password"])) {
    $username = $_POST["Username"];
    $password = $_POST["Password"];
    
    // ดึงข้อมูลผู้ใช้จากฐานข้อมูล
    $stmt = $pdo->prepare("SELECT * FROM Member WHERE Username = ?");
    $stmt->execute([$username]);
    $row = $stmt->fetch();

    // ตรวจสอบรหัสผ่าน
    if ($row && password_verify($password, $row["Password"])) {
        // หากรหัสผ่านถูกต้อง ตั้งค่าข้อมูล session
        session_regenerate_id();
        $_SESSION["Username"] = $row["Username"];
        $_SESSION["Role"] = $row["Role"];

        // ตั้งค่าคุกกี้สำหรับการนับจำนวนการเข้าชม (visit count)
        if (!isset($_COOKIE['visit_count'])) {
            setcookie('visit_count', 1, time() + (86400 * 30), "/"); // เริ่มต้นการนับที่ 1
        } else {
            // หากคุกกี้มีอยู่แล้ว ให้เพิ่มค่า visit count
            $visitCount = (int)$_COOKIE['visit_count'] + 1;
            setcookie('visit_count', $visitCount, time() + (86400 * 30), "/"); // อัปเดตค่าใหม่
        }
        // สร้างคุกกี้ที่มีอายุ ครึ่งชั่วโมง
        setcookie("user_login", "1", time() + 1800, "/"); // ครึ่งชั่วโมง

        echo "เข้าสู่ระบบสำเร็จ";
    } else {
        // กรณีรหัสผ่านไม่ถูกต้อง
        echo "Login ไม่สำเร็จ กรุณาตรวจสอบ Username หรือ Password";
    }
} else {
    echo "กรุณากรอก Username และ Password";
}
?>
