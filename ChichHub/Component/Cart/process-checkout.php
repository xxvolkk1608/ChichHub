<?php
session_start();
include 'connect.php'; // เชื่อมต่อฐานข้อมูล

// รับข้อมูลจากการส่งมาจาก LocalStorage (ตะกร้าสินค้า)
$data = json_decode(file_get_contents('php://input'), true);

// ตรวจสอบว่ามี session ของผู้ใช้หรือไม่
if (!isset($_SESSION['Username'])) {
    echo json_encode(['success' => false, 'message' => 'กรุณาเข้าสู่ระบบก่อนทำการสั่งซื้อ']);
    exit();
}

// ตั้งค่าสถานะคำสั่งซื้อเป็น "waiting"
$status_order = 'waiting';
$username = $_SESSION['Username']; // ดึงชื่อผู้ใช้จาก session

// ตรวจสอบว่ามีสินค้าในตะกร้าหรือไม่
if (!empty($data)) {
    try {
        // เริ่มต้นการทำธุรกรรม
        $pdo->beginTransaction();

        // ดึง ID ของผู้ใช้จาก Username
        $stmt = $pdo->prepare("SELECT ID FROM Member WHERE Username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $userId = $user['ID'];

        // เพิ่มข้อมูลคำสั่งซื้อในตาราง Orders
        $stmt = $pdo->prepare("INSERT INTO Orders (ID, Date, Time) VALUES (?, CURDATE(), CURTIME())");
        $stmt->execute([$userId]);

        // ดึง ID ของคำสั่งซื้อที่เพิ่งถูกเพิ่ม
        $Ord_id = $pdo->lastInsertId();

        // เพิ่มข้อมูลสินค้าในตาราง Ord_detail
        foreach ($data as $item) {
            $stmt = $pdo->prepare("INSERT INTO Ord_detail (P_ID, Ord_id, Amount, Payment_status) VALUES (?, ?, ?, ?)");
            $stmt->execute([$item['id'], $Ord_id, $item['quantity'], 'waiting']);
        }

        // ยืนยันการทำธุรกรรม
        $pdo->commit();

        // ส่งผู้ใช้ไปยังหน้า pay.php พร้อมกับ order_id
        echo json_encode(['success' => true, 'Ord_id' => $Ord_id]);

    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรมหากมีข้อผิดพลาด
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการสั่งซื้อ']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่มีสินค้าในตะกร้า']);
}
