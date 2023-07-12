<?php
    include_once '../data/database.php';
    
    $order_id = $_GET["order_id"];
    $sql = "DELETE FROM Orders WHERE order_id=$order_id";
    $conn->query($sql);

    header("Location: phone_number.php");
?>