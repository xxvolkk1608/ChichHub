<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// ตรวจสอบว่ามีการส่งค่า Ord_id มาหรือไม่
if (isset($_POST['Ord_id'])) {
    $Ord_id = $_POST['Ord_id']; // ดึงค่า Ord_id จากฟอร์ม

    try {
        // เริ่มต้นการทำธุรกรรม
        $pdo->beginTransaction();

        // อัปเดตสถานะการชำระเงินในตาราง Ord_detail โดยเปลี่ยนสถานะเป็น 'paid'
        $stmt = $pdo->prepare("UPDATE Ord_detail SET Payment_status = 'paid' WHERE Ord_id = ?");
        $stmt->execute([$Ord_id]);

        // ตรวจสอบว่ามีการอัปเดตสถานะสำเร็จหรือไม่
        if ($stmt->rowCount() > 0) {
            // แสดงสถานะตะกร้าก่อนลบ
            echo "<pre>ตะกร้าสินค้าก่อนลบ:";
            print_r($_SESSION['cartItems']);
            echo "</pre>";

            // ลบรายการในตะกร้า
            if (isset($_SESSION['cartItems'])) {
                unset($_SESSION['cartItems']); // ลบข้อมูลตะกร้าทั้งหมด
            }

            // แสดงสถานะตะกร้าหลังลบ
            echo "<pre>ตะกร้าสินค้าหลังลบ:";
            print_r($_SESSION['cartItems']);
            echo "</pre>";

            // ยืนยันการทำธุรกรรม
            $pdo->commit();
            echo "การชำระเงินสำเร็จและลบตะกร้าเรียบร้อย";

            // เปลี่ยนเส้นทางไปหน้า "thankyou.php" พร้อม Ord_id
            header("Location: thankyou.php?Ord_id=" . urlencode($Ord_id));
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
    echo "ไม่พบรหัสคำสั่งซื้อ";
}
?>
