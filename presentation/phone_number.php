<?php
include_once 'index.php';
include_once '../data/database.php';
?>
<!DOCTYPE html>
<html>

<head>
    <title>Customer and Order Management</title>
</head>

<body>
    <div class="content">
        <h2>Поиск Клиентов или Заказов по Номеру Телефона</h2>

        <br></br>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <label for="phone_number">Номер Телефона:</label>
            <input type="text" name="phone_number" id="phone_number" list="phone_number_list" autocomplete="off" required> <br></br>
            <datalist id="phone_number_list">
                <?php
                $sql = "SELECT phone_number FROM Customers";
                $result = $conn->query($sql);

                $phone_numbers = array();
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $phone_numbers[] = $row["phone_number"];
                    }
                }
                foreach ($phone_numbers as $number) {
                    echo "<option value='$number'>";
                } ?>
            </datalist>

            <input type="submit" name="submit" value="Найти">
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $phone_number = $_POST["phone_number"];

            $customer_sql = "SELECT * FROM Customers WHERE phone_number LIKE '%$phone_number%'";
            $customer_result = $conn->query($customer_sql);

            if ($customer_result->num_rows > 0) {
                echo "<h3>Найден клиент:</h3>";
                echo "<table>";
                echo "<tr><th>ID</th><th>Имя</th><th>Фамилия</th><th>Номер Телефона</th><th>Действие</th></tr>";

                while ($row = $customer_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["customer_id"] . "</td>";
                    echo "<td>" . $row["first_name"] . "</td>";
                    echo "<td>" . $row["last_name"] . "</td>";
                    echo "<td>" . $row["phone_number"] . "</td>";
                    echo "<td><a class='button' href='customers.php?customer_id=" . $row["customer_id"] . "'>Изменить</a> <a class='button' href='delete_customer.php?customer_id=" . $row["customer_id"] . "' onclick='return confirm(\"Вы уверены, что хотите удалить запись?\")'>Удалить</a></td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "Клиентов не найдено :(";
            }

            $orders_sql = "SELECT * FROM Orders WHERE customer_id IN (SELECT customer_id FROM Customers WHERE phone_number LIKE '%$phone_number%')";
            $orders_result = $conn->query($orders_sql);

            if ($orders_result->num_rows > 0) {
                echo "<h3>Найденные Заказы:</h3>";
                echo "<table>";
                echo "<tr><th>ID</th><th>ID клиента</th><th>Дата Заказа</th><th>Дата Выдачи</th><th>Общая Сумма</th><th>Статус</th><th>Действие</th></tr>";

                while ($row = $orders_result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . $row["order_id"] . "</td>";
                    echo "<td>" . $row["customer_id"] . "</td>";
                    echo "<td>" . $row["order_date"] . "</td>";
                    echo "<td>" . $row["pickup_date"] . "</td>";
                    echo "<td>" . $row["total_amount"] . "</td>";
                    echo "<td>" . $row["status"] . "</td>";
                    echo "<td><a class='button' href='orders.php?order_id=" . $row["order_id"] . "'>Изменить</a> <a href='delete_order.php?order_id=" . $row["order_id"] . "' class='button' onclick='return confirm(\"Вы уверены, что хотите удалить запись?\")'>Удалить</a></td>";
                    echo "</tr>";
                }

                echo "</table>";
            } else {
                echo "Заказов не найдено :(";
            }
        }
        ?>
    </div>
</body>

</html>