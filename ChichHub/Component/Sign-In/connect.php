<?php
<<<<<<< HEAD
// การเชื่อมต่อกับฐานข้อมูล (PDO)
$servername = "localhost";
$username = "root";  // ชื่อผู้ใช้ฐานข้อมูล (ค่าเริ่มต้นของ XAMPP คือ root)
$password = "";  // รหัสผ่านฐานข้อมูล (ค่าเริ่มต้นของ XAMPP คือว่าง)
$dbname = "Testtt";  // ชื่อฐานข้อมูลที่คุณใช้

try {
    // สร้างการเชื่อมต่อฐานข้อมูล
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // ตั้งค่า error mode ให้เป็นการแจ้งเตือนแบบ Exception
=======
    $pdo = new PDO("mysql:host=localhost;dbname=ChicHub;charset=utf8", "root", "");
>>>>>>> 58a66f63b771ddeb104ab13f81ef115ac2338ea5
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
?>

