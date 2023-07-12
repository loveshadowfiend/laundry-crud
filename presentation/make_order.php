<?php
include_once 'index.php';
include_once '../data/database.php';

function sanitize_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

function validatePhoneNumber($phone) {
    $phone = preg_replace('/[^0-9]/', '', $phone);
    $pattern = '/^(?:\+?7|8)?[0-9]{10}$/';

    if (preg_match($pattern, $phone)) {
        return true;
    } else {
        return false;
    }
}

function debug_to_console($data) {
    $output = $data;
    if (is_array($output))
        $output = implode(',', $output);

    echo "<script>console.log('Debug Objects: " . $output . "' );</script>";
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $phone_number = sanitize_input($_POST["phone_number"]); 
    $laundry_type = sanitize_input($_POST["laundry_type"]);
    $order_date = sanitize_input($_POST["order_date"]);
    debug_to_console($order_date);

    $_currentDate = date("Y-m-d");

    if (!validatePhoneNumber($phone_number)) {
        echo "<script>alert('Неправильный номер. Введите в формате +71112223344.');</script>";
    } else if (strtotime($order_date) < strtotime($_currentDate)) {
        echo "<script>alert('Неправильная дата. Введите дату от сегодняшнего дня.');</script>";
    } else {
        $sql = "SELECT * FROM Customers where phone_number='$phone_number'";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $customer_id = $row["customer_id"];

            if ($_POST["laundry_type"] == "Стиральная Машина") {
                $sql = "SELECT i.item_id FROM Items i LEFT JOIN Orders o ON i.item_id = o.item_id WHERE o.status = 'finished' OR o.item_id IS NULL LIMIT 1";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $item_id = $row["item_id"];
                } else {
                    $item_id = "NULL";
                }

                $sql = "INSERT INTO Orders (customer_id, laundry_type, item_id, employee_id, order_date, pickup_date, total_amount, status) VALUES ($customer_id    , '$laundry_type', $item_id, NULL, '$order_date', NULL, NULL, 'В очереди')";
                $conn->query($sql);
            } else {
                $sql = "SELECT e.employee_id FROM Employees e LEFT JOIN Orders o ON e.employee_id = o.employee_id WHERE o.status = 'finished' OR o.employee_id IS NULL LIMIT 1";
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $employee_id = $row["employee_id"];
                } else {
                    $employee_id = "NULL";
                }

                $sql = "INSERT INTO Orders (customer_id, laundry_type, item_id, employee_id, order_date, pickup_date, total_amount, status) VALUES ($customer_id, '$laundry_type', NULL, '$employee_id', '$order_date', NULL, NULL, 'В очереди')";
                $conn->query($sql);
            }

            $order_id = $conn->insert_id;
            header("Location: orders.php?order_id=$order_id");
        } else {
            echo "<script>alert('Клиента не существует в базе данных.');</script>";
        }
    }
}

$sql = "SELECT phone_number FROM Customers";
$result = $conn->query($sql);

$phone_numbers = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $phone_numbers[] = $row["phone_number"];
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Laundry Order</title>
</head>
<body>
    <div class="content">
    <h2>Создать Заказ</h2>

    <br></br>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="phone_number">Номер Телефона:</label>
        <input type="text" name="phone_number" id="phone_number" list="phone_number_list" autocomplete="off" required> <br></br>

        <!-- Phone number suggestions -->
        <datalist id="phone_number_list">
            <?php foreach ($phone_numbers as $number) {
                echo "<option value='$number'>";
            } ?>
        </datalist>

        <label for="laundry_type">Тип:</label>
        <select name="laundry_type" required>
            <option value="Стиральная Машина">Стиральная Машина</option>
            <option value="Химчистка">Химчистка</option>
        </select><br><br>

        <label for="order_date">Дата Заказа:</label>
        <input type="date" name="order_date" value="<?php echo date('Y-m-d'); ?>" required> <br></br>

        <input type="submit" value="Заказать">
    </form>
    </div>
</body>
</html>
