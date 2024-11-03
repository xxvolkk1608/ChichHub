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
                <th>Ord_id</th>
                <th>Amount</th>
                <th>Payment_status</th>
            </tr>
        </thead>
        <tbody>
            <?php
                $stmt = $pdo->prepare("SELECT * FROM Ord_detail");
                $stmt->execute();
                while ($row = $stmt->fetch()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["P_ID"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Ord_id"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Amount"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["Payment_status"]) . "</td>";
                    echo "</tr>";
                }
            ?>
        </tbody>
    </table>
</body>
</html>
