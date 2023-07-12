<?php
include_once '../data/database.php';
include_once 'index.php'
?>

<!DOCTYPE html>
<html>

<head>
    <title>Items</title>
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
                $item_id = sanitize_input($_POST["item_id"]);
                $item_name = sanitize_input($_POST["item_name"]);
                $price = sanitize_input($_POST["price"]);
                $description = sanitize_input($_POST["description"]);

                if (empty($item_id)) {
                    $sql = "INSERT INTO Items (item_name, price, description) VALUES ('$item_name', $price, '$description')";
                } else {
                    $sql = "UPDATE Items SET item_name='$item_name', price=$price, description='$description' WHERE item_id=$item_id";
                }

                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }

            if (isset($_POST["delete"])) {
                $item_id = sanitize_input($_POST["item_id"]);

                $sql = "DELETE FROM Items WHERE item_id=$item_id";

                if ($conn->query($sql) !== TRUE) {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        $sql = "SELECT * FROM Items";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
        ?>
            <h2>Стиральные Машинки</h2>

            <br></br>

            <table>
                <tr>
                    <th>ID</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Описание</th>
                    <th>Действие</th>
                </tr>
                <?php
                while ($row = $result->fetch_assoc()) {
                ?>
                    <tr>
                        <td><?php echo $row["item_id"]; ?></td>
                        <td><?php echo $row["item_name"]; ?></td>
                        <td><?php echo $row["price"]; ?></td>
                        <td><?php echo $row["description"]; ?></td>
                        <td>
                            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                                <input type="hidden" name="item_id" value="<?php echo $row["item_id"]; ?>">
                                <input type="submit" name="edit" value="Изменить">
                                <input type="submit" name="delete" value="Удалить" onclick="return confirm('Вы уверены, что хотите удалить запись?')">
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

        if (isset($_POST["edit"])) {
            $item_id = sanitize_input($_POST["item_id"]);

            $edit_sql = "SELECT * FROM Items WHERE item_id=$item_id";
            $edit_result = $conn->query($edit_sql);

            if ($edit_result->num_rows == 1) {
                $edit_row = $edit_result->fetch_assoc();
            }
        }
        ?>

        <h2>Стиральная Машинка</h2>

        <br></br>

        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="hidden" name="item_id" value="<?php if (isset($edit_row)) echo $edit_row['item_id']; ?>">
            <label for="item_name">Название:</label>
            <input type="text" name="item_name" value="<?php if (isset($edit_row)) echo $edit_row['item_name']; ?>" required><br><br>
            <label for="price">Цена:</label>
            <input type="number" name="price" step="0.01" value="<?php if (isset($edit_row)) echo $edit_row['price']; ?>" required><br><br>
            <label for="description">Описание:</label>
            <input type="text" name="description" value="<?php if (isset($edit_row)) echo $edit_row['description']; ?>" required><br><br>
            <input type="submit" name="save" value="<?php if (isset($edit_row)) echo 'Обновить';
                                                    else echo 'Сохранить'; ?>">
        </form>
    </div>
</body>

</html>