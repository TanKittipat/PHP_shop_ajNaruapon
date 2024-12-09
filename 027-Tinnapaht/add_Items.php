<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include 'shopDB.php';

    // Sanitize and validate inputs
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? null;
    $descriptions = trim($_POST['descriptions'] ?? '');
    $images_url = trim($_POST['images_url'] ?? '');
    $quantity = $_POST['quantity'] ?? null;

    // Check for required fields
    if (empty($name) || empty($price) || empty($stock_quantity)) {
        echo "กรุณากรอกข้อมูลที่จำเป็นทั้งหมด!";
        exit;
    }

    // Ensure numeric fields are valid
    if (!is_numeric($price) || !is_numeric($stock_quantity)) {
        echo "กรุณากรอกราคาหรือจำนวนสินค้าเป็นตัวเลข!";
        exit;
    }

    try {
        // Prepare SQL statement
        $sql = "INSERT INTO products (name, category, price, descriptions, images_url, quantity) 
                VALUES (:name, :category, :price, :descriptions, :images_url, :quantity)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':descriptions', $descriptions);
        $stmt->bindParam(':images_url', $images_url);
        $stmt->bindParam(':quantity', $quantity);

        // Execute the statement
        $stmt->execute();

        echo "เพิ่มสินค้าเรียบร้อยแล้ว!";
    } catch (PDOException $e) {
        // Log the error for debugging (optional)
        error_log("Database Error: " . $e->getMessage());
        echo "เกิดข้อผิดพลาดในการเพิ่มสินค้า!";
    }

    // Close the connection
    $conn = null;
}
?>
