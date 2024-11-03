<?php
// เริ่มต้นการใช้งาน session
session_start();

// ลบข้อมูลทั้งหมดใน session
session_unset();

// ทำลาย session
session_destroy();

// เปลี่ยนเส้นทางไปยังหน้าเข้าสู่ระบบหรือหน้าหลัก
header("Location: ../Sign-In/signin.php");
exit();
?>
