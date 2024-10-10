<?php
// การเชื่อมต่อกับฐานข้อมูล (PDO)
$servername = "localhost";
$username = "root";  // ชื่อผู้ใช้ฐานข้อมูล (ค่าเริ่มต้นของ XAMPP คือ root)
$password = "";  // รหัสผ่านฐานข้อมูล (ค่าเริ่มต้นของ XAMPP คือว่าง)
$dbname = "ChicHub";  // ชื่อฐานข้อมูลที่คุณใช้

try {
    // สร้างการเชื่อมต่อฐานข้อมูล
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // ตั้งค่า error mode ให้เป็นการแจ้งเตือนแบบ Exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    exit();  // หยุดการทำงานถ้าเชื่อมต่อไม่ได้
}
?>
