<?php
include '../db_connection.php';
session_start();
$user_id = $_SESSION['user_id'];



?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>home</title>
    <!-- custom css file link -->
    
</head>
<body>
    
<div>
    <h1>Hello world</h1>


    <?php
    // ตรวจสอบว่าการเชื่อมต่อสำเร็จแล้ว
    $select = mysqli_query($conn, "SELECT * FROM Member") or die('query failed');

    // แสดงผลข้อมูลที่ query มา
    if(mysqli_num_rows($select) > 0){
        while($row = mysqli_fetch_assoc($select)){
            echo "ID: " . $row['ID'] . "<br>";
            echo "Name: " . $row['Username'] . "<br>";
            echo "password: " . $row['Password'] . "<br>";
            echo "md_id: " . $row['MD_ID'] . "<br><br>";
        }
    } else {
        echo "ไม่มีข้อมูลในตาราง";
    }
?>

</div>


</body>
</html>