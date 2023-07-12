<?php
    // Database connection details
    $servername = "localhost";
    $username = "root";
    $password = "banana6346";
    $dbname = "laundry";

    // Create a database connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
?>