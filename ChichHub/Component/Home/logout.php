<?php
// เริ่มต้นการใช้งาน session
session_start();

// ลบข้อมูลทั้งหมดใน session
session_unset();

// ทำลาย session
session_destroy();

// ลบ cookies โดยการตั้งค่า expire เป็นเวลาในอดีต
setcookie("Username", "", time() - 3600, "/");

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบ
header("Location: ../Sign-In/signin.php");
exit();
?>