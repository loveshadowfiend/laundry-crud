<?php
include_once 'index.php';
include_once '../data/database.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Order Status Search</title>
</head>

<body>
    <div class="content">
        <h2>Поиск Заказов по Статусу</h2><br></br>


        <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="status">Статус:</label>
            <select name="status" required>
                <option value="В очереди">В очереди</option>
                <option value="В процессе">В процессе</option>
                <option value="Закончено">Закончено</option>
            </select>
            <button type="submit">Найти</button>
        </form><br></br>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $status = $_POST["status"];

            $sql = "SELECT * FROM Orders WHERE status = '$status'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                echo "<h2>Заказы со Статусом: $status</h2><br></br>";
                echo "<table>";
                echo "<tr>
                    <th>ID</th>
                    <th>ID клиента</th>
                    <th>Дата заказа</th>
                    <th>Дата выдачи</th>
                    <th>Общая сумма</th>
                    <th>Статус</th>
                  </tr>";
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["order_id"] . "</td>";
                    echo "<td>" . $row["customer_id"] . "</td>";
                    echo "<td>" . $row["order_date"] . "</td>";
                    echo "<td>" . $row["pickup_date"] . "</td>";
                    echo "<td>" . $row["total_amount"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "</tr>";
                }
                echo "</table>";
            } else {
                echo "Ничего не найдено :(";
            }
        }
        ?>
    </div>
</body>

</html>