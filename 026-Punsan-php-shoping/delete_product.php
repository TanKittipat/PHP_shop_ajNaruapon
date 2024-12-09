<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn = new mysqli('localhost', 'root', '', 'shop');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
    
    $stmt = $conn->prepare("DELETE FROM products WHERE id=?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    
    echo "สินค้าถูกลบสำเร็จ!";
    $stmt->close();
    $conn->close();

    // รีไดเรกไปยังหน้า manage_products.php
    header("Location: manage_products.php");
    exit();
}
?>
