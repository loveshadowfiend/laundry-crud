<?php
    include_once '../data/database.php';
    
    $customer_id = $_GET["customer_id"];
    $sql = "DELETE FROM Customers WHERE customer_id=$customer_id";
    $conn->query($sql);

    header("Location: phone_number.php");
