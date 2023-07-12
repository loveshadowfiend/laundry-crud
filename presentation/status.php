<?php
    include_once '../data/database.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Order Status Search</title>
</head>
<body>
    <form method="GET" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
        <label for="status">Select Status:</label>
        <select name="status" required>
            <option value="Pending">Pending</option>
            <option value="In Process">In Process</option>
            <option value="Finished">Finished</option>
        </select>
        <button type="submit">Search</button>
    </form>

    <?php
    // Check if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        // Retrieve the selected status from the form
        $status = $_GET["status"];

        // Sanitize the selected status
        $status = mysqli_real_escape_string($conn, $status);

        // Perform the query
        $sql = "SELECT * FROM Orders WHERE status = '$status'";
        $result = $conn->query($sql);

        // Display the results
        if ($result->num_rows > 0) {
            echo "<h2>Orders with Status: $status</h2>";
            echo "<table>";
            echo "<tr>
                    <th>Order ID</th>
                    <th>Customer ID</th>
                    <th>Order Date</th>
                    <th>Pickup Date</th>
                    <th>Total Amount</th>
                    <th>Status</th>
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
            echo "No orders found with the selected status.";
        }

        // Close the database connection
        $conn->close();
    }
    ?>
</body>
</html>
