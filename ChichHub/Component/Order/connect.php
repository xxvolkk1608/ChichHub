<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=sec2_18;charset=utf8", "Tstd18", "64gJY16w");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "เกิดข้อผิดพลาด : ".$e->getMessage();
}
?>