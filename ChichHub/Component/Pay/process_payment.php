<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่า Ord_id, shipping_address และ payment_method มาหรือไม่
if (
    isset($_POST['Ord_id']) && !empty($_POST['Ord_id']) &&
    isset($_POST['shipping_address']) && !empty($_POST['shipping_address']) &&
    isset($_POST['payment_method']) && !empty($_POST['payment_method'])
) {
    $Ord_id = $_POST['Ord_id']; // ดึงค่า Ord_id จากฟอร์ม
    $shipping_address = $_POST['shipping_address']; // ดึงที่อยู่การจัดส่งจากฟอร์ม
    $payment_method = $_POST['payment_method']; // ดึงวิธีการชำระเงินจากฟอร์ม

    try {
        // เริ่มต้นการทำธุรกรรม
        $pdo->beginTransaction();

        // อัปเดตสถานะการชำระเงินในตาราง Ord_detail โดยเปลี่ยนสถานะเป็น 'paid'
        $stmt = $pdo->prepare("UPDATE Ord_detail SET Payment_status = 'paid' WHERE Ord_id = ?");
        $stmt->execute([$Ord_id]);

        // ตรวจสอบว่ามีการอัปเดตสถานะสำเร็จหรือไม่
        if ($stmt->rowCount() > 0) {
            // บันทึกที่อยู่การจัดส่งและวิธีการชำระเงินลงในตาราง Orders
            $stmt = $pdo->prepare("UPDATE Orders SET shipping_address = ?, payment_method = ? WHERE Ord_id = ?");
            $stmt->execute([$shipping_address, $payment_method, $Ord_id]);

            // ตรวจสอบว่ามี cartItems อยู่ใน session หรือไม่ก่อนลบ
            if (isset($_SESSION['cartItems'])) {
                // ลบรายการในตะกร้า
                unset($_SESSION['cartItems']); // ลบข้อมูลตะกร้าทั้งหมด
            }

            // ยืนยันการทำธุรกรรม
            $pdo->commit();

            // ป้องกันการส่งข้อมูลซ้ำ
            session_regenerate_id(true);

            // เปลี่ยนเส้นทางไปหน้า "thankyou.php" พร้อม Ord_id
            header("Location: thankyou.php?Ord_id=" . urlencode($Ord_id));
            exit();
        } else {
            echo "ไม่พบรหัสคำสั่งซื้อหรือเกิดข้อผิดพลาดในการอัปเดตคำสั่งซื้อ";
            $pdo->rollBack();
        }
    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรมหากมีข้อผิดพลาด
        $pdo->rollBack();
        echo "เกิดข้อผิดพลาดในการชำระเงิน: " . $e->getMessage();
    }
} else {
    echo "ไม่พบรหัสคำสั่งซื้อหรือข้อมูลการจัดส่ง/การชำระเงินไม่สมบูรณ์";
}
?>
 
