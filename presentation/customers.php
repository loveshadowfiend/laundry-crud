<?php
include_once 'index.php';
include_once '../data/database.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Customers</title>
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
                $customer_id = sanitize_input($_POST["customer_id"]);
                $first_name = sanitize_input($_POST["first_name"]);
                $last_name = sanitize_input($_POST["last_name"]);
                $phone_number = sanitize_input($_POST["phone_number"]);

                if (empty($customer_id)) {
                    $sql = "INSERT INTO Customers (first_name, last_name, phone_number) VALUES ('$first_name', '$last_name', '$phone_number')";
                } else {
                    $sql = "UPDATE Customers SET first_name='$first_name', last_name='$last_name', phone_number='$phone_number' WHERE customer_id=$customer_id";
                }

                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                } else if (isset($_GET["customer_id"])) {
                    header("Location: phone_number.php");
                }
            }

            if (isset($_POST["delete"])) {
                $customer_id = sanitize_input($_POST["customer_id"]);

                $sql = "DELETE FROM Customers WHERE customer_id=$customer_id";

                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                } else if (isset($_GET["customer_id"])) {
                    header("Location: phone_number.php");
                }
            }
        }

        if (isset($_GET["customer_id"])) {
            $customer_id = $_GET["customer_id"];
            $sql = "SELECT * FROM Customers WHERE customer_id=$customer_id";
        } else {
            $sql = "SELECT * FROM Customers";
        }
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        ?>
            <h2>Клиенты</h2>

            <br></br>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Имя</th>
                    <th>Фамилия</th>
                    <th>Номер Телефона</th>
                    <th>Действие</th>
                </tr>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $row["customer_id"]; ?></td>
                        <td><?php echo $row["first_name"]; ?></td>
                        <td><?php echo $row["last_name"]; ?></td>
                        <td><?php echo $row["phone_number"]; ?></td>
                        <td>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="customer_id" value="<?php echo $row["customer_id"]; ?>">
                                <button type="submit" name="edit" value="edit">Изменить</button>
                                <button type="submit" name="delete" value="delete" onclick="return confirm('Вы уверены, что хотите удалить запись?')">Удалить</button>
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

        // Retrieve customer details for editing
        if (isset($_POST["edit"]) || isset($_GET["customer_id"])) {
            if (isset($_GET["customer_id"])) {
                $customer_id = sanitize_input($_GET["customer_id"]);
            } else {
                $customer_id = sanitize_input($_POST["customer_id"]);
            }

            $edit_sql = "SELECT * FROM Customers WHERE customer_id=$customer_id";
            $edit_result = $conn->query($edit_sql);

            if ($edit_result->num_rows == 1) {
                $edit_row = $edit_result->fetch_assoc();
            }
        }
        ?>
    </div>
</body>

</html>