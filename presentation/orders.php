<?php
include_once 'index.php';
include_once '../data/database.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Order CRUD Form</title>
</head>

<body>
    <div class="content">
        <?php
        function sanitize_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            if (isset($_POST["save"])) {
                $order_id = sanitize_input($_POST["order_id"]);
                $customer_id = sanitize_input($_POST["customer_id"]);
                $employee_id = sanitize_input($_POST["employee_id"]);
                $item_id = sanitize_input($_POST["item_id"]);
                $laundry_type = sanitize_input($_POST["laundry_type"]);
                $order_date = sanitize_input($_POST["order_date"]);
                $pickup_date = sanitize_input($_POST["pickup_date"]);
                if ($pickup_date == NULL) {
                    $pickup_date = "NULL";
                } else {
                    $pickup_date = "'" . $pickup_date . "'";
                }
                $total_amount = sanitize_input($_POST["total_amount"]);
                $status = sanitize_input($_POST["status"]);

                if (empty($order_id)) {
                    $sql = "INSERT INTO Orders (customer_id, laundry_type, item_id, employee_id, order_date, pickup_date, total_amount, status) VALUES ($customer_id, '$laundry_type', $item_id, $employee_id, '$order_date', $pickup_date, $total_amount, '$status')";
                } else {
                    $sql = "UPDATE Orders SET customer_id=$customer_id, laundry_type='$laundry_type', item_id=$item_id, employee_id=$employee_id, order_date='$order_date', pickup_date=$pickup_date, total_amount=$total_amount, status='$status' WHERE order_id=$order_id";
                }

                if ($conn->query($sql) === TRUE) {
                    if (isset($_GET["order_id"])) {
                        header("Location: phone_number.php");
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            if (isset($_POST["delete"])) {
                $order_id = sanitize_input($_POST["order_id"]);

                $sql = "DELETE FROM Orders WHERE order_id=$order_id";

                if ($conn->query($sql) === TRUE) {
                    if (isset($_GET["order_id"])) {
                        header("Location: phone_number.php");
                    }
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        if (isset($_GET["order_id"])) {
            $order_id = sanitize_input($_GET["order_id"]);
            $sql = "SELECT * FROM Orders WHERE order_id=$order_id";
        } else {
            $sql = "SELECT * FROM Orders";
        }
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        ?>
            <h2>Заказы</h2>

            <br></br>

            <table>
                <tr>
                    <th>ID</th>
                    <th>ID клиента</th>
                    <th>Тип</th>
                    <th>ID машинки</th>
                    <th>ID работника</th>
                    <th>Дата Заказа</th>
                    <th>Дата Выдачи</th>
                    <th>Общая Сумма</th>
                    <th>Статус</th>
                    <th>Действие</th>
                </tr>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $row["order_id"]; ?></td>
                        <td><?php echo $row["customer_id"]; ?></td>
                        <td><?php echo $row["laundry_type"]; ?></td>
                        <td><?php echo $row["item_id"]; ?></td>
                        <td><?php echo $row["employee_id"]; ?></td>
                        <td><?php echo $row["order_date"]; ?></td>
                        <td><?php echo $row["pickup_date"]; ?></td>
                        <td><?php echo $row["total_amount"]; ?></td>
                        <td><?php echo $row["status"]; ?></td>
                        <td>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="order_id" value="<?php echo $row["order_id"]; ?>">
                                <input type="submit" name="edit" value="Изменить">
                                <input type="submit" name="delete" value="Удалить" onclick="return confirm('Are you sure you want to delete this order?')">
                            </form>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </table>

            <br></br>
        <?php
        }

        if (isset($_POST["edit"]) || isset($_GET["order_id"])) {
            if (isset($_POST["edit"])) {
                $order_id = sanitize_input($_POST["order_id"]);
            } else {
                $order_id = sanitize_input($_GET["order_id"]);
            }

            $edit_sql = "SELECT * FROM Orders WHERE order_id=$order_id";
            $edit_result = $conn->query($edit_sql);

            if ($edit_result->num_rows == 1) {
                $edit_row = $edit_result->fetch_assoc();
            }
        }

        $customer_sql = "SELECT customer_id, first_name, last_name, phone_number FROM Customers";
        $customer_result = $conn->query($customer_sql);

        $item_sql = "SELECT item_id, item_name, price, description FROM Items";
        $item_result = $conn->query($item_sql);

        $employee_sql = "SELECT employee_id, first_name, last_name FROM Employees";
        $employee_result = $conn->query($employee_sql);
        ?>

        <h2>Заказ</h2>

        <br></br>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="order_id" value="<?php if (isset($edit_row)) echo $edit_row['order_id']; ?>">

            <label for="customer_id">Клиент:</label>
            <select name="customer_id" required>
                <?php
                while ($customer_row = $customer_result->fetch_assoc()) {
                    $selected_customer = isset($edit_row) && $edit_row['customer_id'] == $customer_row['customer_id'] ? 'selected' : '';
                    echo "<option value='{$customer_row['customer_id']}' {$customer_row['customer_id']} $selected_customer>{$customer_row['first_name']} {$customer_row['last_name']} {$customer_row['phone_number']}</option>";
                }
                ?>
            </select><br><br>

            <label for="laundry_type">Тип Заказа:</label>
            <select id="laundry_type" name="laundry_type" onchange="toggleFields()">
                <option value="Стиральная Машина" <?php
                                                    if (isset($edit_row) && $edit_row['laundry_type'] == 'Стиральная Машина') echo "selected";
                                                    ?>>Стиральная Машина</option>
                <option value="Химчистка" <?php
                                            if (isset($edit_row) && $edit_row['laundry_type'] == 'Химчистка') echo "selected";
                                            ?>>Химчистка</option>
            </select><br><br>

            <div id="item_field" style="display: <?php
                                                    if (isset($edit_row)) {
                                                        echo $edit_row['laundry_type'] == 'Washing Machine' ? 'block' : 'none';
                                                    } else {
                                                        echo 'block';
                                                    } ?>">
                <label for="item_id">Стиральная Машина:</label>
                <select name="item_id" required>
                    <?php
                    while ($item_row = $item_result->fetch_assoc()) {
                        $selected_item = isset($edit_row) && $edit_row['item_id'] == $item_row['item_id'] ? 'selected' : '';
                        echo "<option value='{$item_row['item_id']}' $selected_item>{$item_row['item_name']} - {$item_row['price']}</option>";
                    }
                    ?>
                </select><br><br>
            </div>

            <div id="employee_field" style="display: <?php echo isset($edit_row) && $edit_row['laundry_type'] == 'Химчистка' ? 'block' : 'none'; ?>">
                <label for="employee_id">Работник:</label>
                <select name="employee_id">
                    <?php
                    while ($employee_row = $employee_result->fetch_assoc()) {
                        $selected_employee = isset($edit_row) && $edit_row['employee_id'] == $employee_row['employee_id'] ? 'selected' : '';
                        echo "<option value='{$employee_row['employee_id']}' $selected_employee>{$employee_row['first_name']} {$employee_row['last_name']}</option>";
                    }
                    ?>
                </select><br><br>
            </div>

            <label for="order_date">Дата Заказа:</label>
            <input type="date" name="order_date" value="<?php if (isset($edit_row)) echo $edit_row['order_date'];
                                                        else echo date('Y-m-d'); ?>" required><br><br>

            <label for="pickup_date">Дата Выдачи:</label>
            <input type="date" name="pickup_date" value="<?php if (isset($edit_row)) echo $edit_row['pickup_date']; ?>"><br><br>

            <label for="total_amount">Общая Сумма:</label>
            <input type="number" name="total_amount" step="0.01" value="<?php if (isset($edit_row)) echo $edit_row['total_amount']; ?>" required><br><br>

            <label for="status">Статус:</label>
            <select name="status" required>
                <option value="В очереди" <?php
                                            if (isset($edit_row) && $edit_row['status'] == 'В очереди') echo "selected";
                                            ?>>В очереди</option>
                <option value="В процессе" <?php
                                            if (isset($edit_row) && $edit_row['status'] == 'В процессе') echo "selected";
                                            ?>>В процессе</option>
                <option value="Закончено" <?php
                                            if (isset($edit_row) && $edit_row['status'] == 'Закончено') echo "selected";
                                            ?>>Закончено</option>
            </select><br><br>

            <input type="submit" name="save" value="<?php if (isset($edit_row)) echo 'Обновить';
                                                    else echo 'Сохранить'; ?>">
        </form>
    </div>
</body>
<script>
    function toggleFields() {
        var laundryType = document.getElementById("laundry_type").value;
        var itemField = document.getElementById("item_field");
        var employeeField = document.getElementById("employee_field");

        if (laundryType === "Стиральная Машина") {
            itemField.style.display = "block";
            employeeField.style.display = "none";
        } else if (laundryType === "Химчистка") {
            itemField.style.display = "none";
            employeeField.style.display = "block";
        } else {
            itemField.style.display = "none";
            employeeField.style.display = "none";
        }
    }
</script>

</html>