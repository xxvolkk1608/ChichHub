<?php
// เปิดการแสดงผลข้อผิดพลาด (ถ้ามี)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include 'connect.php'; // เชื่อมต่อกับฐานข้อมูล

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // รับข้อมูลจากฟอร์ม
    $username = $_POST['username'];
    $password = $_POST['password'];  // อย่าลืมใช้ hash password จริงๆ ในการสมัครสมาชิก
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    try {
        // เริ่มต้นการทำธุรกรรม
        $conn->beginTransaction();

        // เพิ่มข้อมูลลงในตาราง `member_detail` ก่อน
        $sql_member_detail = "INSERT INTO member_detail (Name, Surname, Email, Tel, Address) VALUES (?, ?, ?, ?, ?)";
        $stmt_member_detail = $conn->prepare($sql_member_detail);
        $stmt_member_detail->execute([$name, $surname, $email, $phone, $address]);

        // ดึง `MD_ID` ของ `member_detail` ที่เพิ่งเพิ่มเข้ามาใหม่
        $md_id = $conn->lastInsertId();

        // เพิ่มข้อมูลลงในตาราง `member` พร้อมกับ `MD_ID` ที่ได้จากการเพิ่มข้อมูลใน `member_detail`
        $sql_member = "INSERT INTO member (Username, Password, MD_ID) VALUES (?, ?, ?)";
        $stmt_member = $conn->prepare($sql_member);
        $stmt_member->execute([$username, $password, $md_id]);

        // ถ้าทุกอย่างสำเร็จ ให้ commit การทำงาน
        $conn->commit();

        // ส่งผู้ใช้ไปที่หน้าสำเร็จ
        header("Location: success-page.php");
        exit();
    } catch (Exception $e) {
        // ถ้ามีปัญหาให้ rollback การทำงาน
        $conn->rollBack();
        echo "Error: " . $e->getMessage();
    }
}
