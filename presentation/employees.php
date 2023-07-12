<?php 
    include_once '../data/database.php';
    include_once 'index.php';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Employee CRUD Form</title>
</head>
<body>
    <div class="content">
    <?php
    // Create a function to sanitize user inputs
    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Handle form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Create or update employee
        if (isset($_POST["save"])) {
            $employee_id = sanitize_input($_POST["employee_id"]);
            $first_name = sanitize_input($_POST["first_name"]);
            $last_name = sanitize_input($_POST["last_name"]);
            $description = sanitize_input($_POST["description"]);

            // Check if it's a new employee or an update
            if (empty($employee_id)) {
                // Insert a new employee
                $sql = "INSERT INTO Employees (first_name, last_name, description) VALUES ('$first_name', '$last_name', '$description')";
            } else {
                // Update an existing employee
                $sql = "UPDATE Employees SET first_name='$first_name', last_name='$last_name', description='$description' WHERE employee_id=$employee_id";
            }

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }

        // Delete employee
        if (isset($_POST["delete"])) {
            $employee_id = sanitize_input($_POST["employee_id"]);

            // Delete the employee from the database
            $sql = "DELETE FROM Employees WHERE employee_id=$employee_id";

            if ($conn->query($sql) !== TRUE) {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
    }

    // Retrieve existing employees from the database
    $sql = "SELECT * FROM Employees";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        ?>
        <h2>Работники</h2>

        <br></br>

        <table>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Фамилия</th>
                <th>Описание</th>
                <th>Действие</th>
            </tr>
            <?php
            while ($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row["employee_id"]; ?></td>
                    <td><?php echo $row["first_name"]; ?></td>
                    <td><?php echo $row["last_name"]; ?></td>
                    <td><?php echo $row["description"]; ?></td>
                    <td>
                        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                            <input type="hidden" name="employee_id" value="<?php echo $row["employee_id"]; ?>">
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

    // Retrieve employee details for editing
    if (isset($_POST["edit"])) {
        $employee_id = sanitize_input($_POST["employee_id"]);

        $edit_sql = "SELECT * FROM Employees WHERE employee_id=$employee_id";
        $edit_result = $conn->query($edit_sql);

        if ($edit_result->num_rows == 1) {
            $edit_row = $edit_result->fetch_assoc();
        }
    }
    ?>

    <h2>Работник</h2>

    <br></br>

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <input type="hidden" name="employee_id" value="<?php if (isset($edit_row)) echo $edit_row['employee_id']; ?>">
        <label for="first_name">Имя:</label>
        <input type="text" name="first_name" value="<?php if (isset($edit_row)) echo $edit_row['first_name']; ?>" required><br><br>
        <label for="last_name">Фамилия:</label>
        <input type="text" name="last_name" value="<?php if (isset($edit_row)) echo $edit_row['last_name']; ?>"><br><br>
        <label for="description">Описание:</label>
        <input type="text" name="description" value="<?php if (isset($edit_row)) echo $edit_row['description']; ?>"><br><br>
        <input type="submit" name="save" value="<?php if (isset($edit_row)) echo 'Обновить'; else echo 'Сохранить'; ?>">
    </form>
    
    </div>
</body>
</html>