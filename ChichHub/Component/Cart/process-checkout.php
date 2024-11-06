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

        if (!$user) {
            throw new Exception("ไม่พบผู้ใช้ในระบบ");
        }

        $userId = $user['ID'];

        // คำนวณราคารวมและตรวจสอบเงื่อนไขโปรโมชั่น
        $totalPrice = 0;
        foreach ($data as $item) {
            $totalPrice += $item['price'] * $item['quantity'];
        }

        // ตรวจสอบว่ามียอดรวมเกิน 2000 บาทหรือไม่
        if ($totalPrice > 2000) {
            $finalPrice = $totalPrice * 0.8; // ลด 20%
        } else {
            $finalPrice = $totalPrice;
        }

        // เพิ่มข้อมูลคำสั่งซื้อในตาราง Orders พร้อมกับ final_price
        $stmt = $pdo->prepare("INSERT INTO Orders (ID, Date, Time, final_price) VALUES (?, CURDATE(), CURTIME(), ?)");
        $stmt->execute([$userId, $finalPrice]);

        // ดึง ID ของคำสั่งซื้อที่เพิ่งถูกเพิ่ม
        $Ord_id = $pdo->lastInsertId();

        // เพิ่มข้อมูลสินค้าในตาราง Ord_detail
        foreach ($data as $item) {
            // ตรวจสอบว่าสินค้าที่มี P_ID นี้มีสถานะ waiting อยู่หรือไม่
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM Ord_detail WHERE P_ID = ? AND Payment_status = 'waiting'");
            $stmt->execute([$item['id']]);
            $count = $stmt->fetchColumn();

            // ถ้าไม่มีสถานะ waiting ให้เพิ่มข้อมูลใหม่ลงฐานข้อมูล
            if ($count == 0) {
                $stmt = $pdo->prepare("INSERT INTO Ord_detail (P_ID, Ord_id, Amount, Payment_status) VALUES (?, ?, ?, ?)");
                $stmt->execute([$item['id'], $Ord_id, $item['quantity'], 'waiting']);
            }
        }

        // ยืนยันการทำธุรกรรม
        $pdo->commit();

        // ส่งผู้ใช้ไปยังหน้า pay.php พร้อมกับ order_id
        echo json_encode(['success' => true, 'Ord_id' => $Ord_id]);

    } catch (Exception $e) {
        // ยกเลิกการทำธุรกรรมหากมีข้อผิดพลาด
        $pdo->rollBack();
        echo json_encode(['success' => false, 'message' => 'เกิดข้อผิดพลาดในการสั่งซื้อ: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'ไม่มีสินค้าในตะกร้า']);
}