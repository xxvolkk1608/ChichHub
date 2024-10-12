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
                <th>Ord_id</th>
                <th>Date</th>
                <th>Time</th>
                <th>ID</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $stmt = $pdo->prepare("SELECT * FROM Orders");
                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["Ord_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Date"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Time"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["ID"]) . "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
