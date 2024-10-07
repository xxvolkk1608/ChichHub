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
                <th>P_ID</th>
                <th>Price</th>
                <th>Amount</th>
                <th>C_ID</th>
                <th>IMG_ID</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $stmt = $pdo->prepare("SELECT * FROM Product");
                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["P_ID"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Price"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Amount"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["C_ID"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["IMG_ID"]) . "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
