<?php
require "db.php";

if (isset($_GET['id'])) {

    $query = "DELETE FROM items WHERE id = :id";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $_GET['id']);

    if ($stmt->execute()) {
        $mess = "Item Deleted!!!";
        header('location:index.php');
    } else {
        $mess = "Failed Delete!!!";
    }

    echo $mess;
    $conn = null;
}
