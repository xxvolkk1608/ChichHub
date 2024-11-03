<?php include "connect.php"; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        table {
            width: 30%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 4px;
            text-align: center;
        }

    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>รหัส Images</th>
                <th>ชื่อ File</th>
                <th>วันที่ Upload</th>
                <th>Path</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $stmt = $pdo->prepare("SELECT * FROM Images");
                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["IMG_ID"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["File_name"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Upload_date"]) . "</td>";
                    echo "<td>" . "Path/Path" . "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
