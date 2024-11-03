<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่า ord_id และ payment_method มาหรือไม่
if (isset($_POST['Ord_id'])) {
    $Ord_id = $_POST['Ord_id']; // ดึงค่า ord_id จากฟอร์ม

    try {
        // เริ่มต้นการทำธุรกรรม
        $pdo->beginTransaction();

        // อัปเดตสถานะการชำระเงินในตาราง Ord_detail โดยเปลี่ยนสถานะเป็น 'paid'
        $stmt = $pdo->prepare("UPDATE Ord_detail SET Payment_status = 'paid' WHERE Ord_id = ?");
        $stmt->execute([$Ord_id]);

        // ตรวจสอบว่ามีการอัปเดตสถานะสำเร็จหรือไม่
        if ($stmt->rowCount() > 0) {
            
            echo "<pre>";
            print_r($_SESSION['cartItems']); // แสดงก่อนการลบ
            echo "</pre>";

            if (isset($_SESSION['cartItems'])) {
                foreach ($_SESSION['cartItems'] as $key => $item) {
                    unset($_SESSION['cartItems'][$key]); // ลบแต่ละรายการในตะกร้า
                }
            }

            echo "<pre>";
            print_r($_SESSION['cartItems']); // แสดงหลังการลบ
            echo "</pre>";

            // ยืนยันการทำธุรกรรม
            $pdo->commit();
            echo "การชำระเงินสำเร็จและลบตะกร้าเรียบร้อย";
            // เปลี่ยนเส้นทางไปหน้า "thankyou.php"
            header("Location: thankyou.php");
            exit();
        } else {
            echo "ไม่พบรหัสคำสั่งซื้อหรือเกิดข้อผิดพลาดในการอัปเดตคำสั่งซื้อ";
        }
    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรมหากมีข้อผิดพลาด
        $pdo->rollBack();
        echo "เกิดข้อผิดพลาดในการชำระเงิน: " . $e->getMessage();
    }
} else {
    echo "ไม่พบรหัสคำสั่งซื้อหรือวิธีการชำระเงิน";
}

