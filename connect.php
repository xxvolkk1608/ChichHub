<!-- For Local Host -->

<?php
try {
    $pdo = new PDO("mysql:host=localhost;dbname=ChicHub;charset=utf8", "root", "");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "เกิดข้อผิดพลาด : ".$e->getMessage();
}
?>


<!-- For Web Server -->

<?php
try {
    $pdo = new PDO("mysql:host=localhost; dbname=sec2_16; charset=utf8", "Tstd16", "7G0P6HVH");
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
	echo "เกิดข้อผิดพลาด : ".$e->getMessage();
}
?>