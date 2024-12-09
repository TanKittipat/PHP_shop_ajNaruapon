<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include 'connectDB.php';

    // Sanitize and validate inputs
    $name = trim($_POST['name'] ?? '');
    $category = trim($_POST['category'] ?? '');
    $price = $_POST['price'] ?? null;
    $description = trim($_POST['description'] ?? '');
    $image_url = trim($_POST['image_url'] ?? '');
    $stock_quantity = $_POST['stock_quantity'] ?? null;

    // Convert attributes to JSON
    $attributes_json = json_encode(explode(",", $attributes));

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
        $sql = "INSERT INTO products (name, category, price, description, image_url, attributes, stock_quantity) 
                VALUES (:name, :category, :price, :description, :image_url, :attributes, :stock_quantity)";
        $stmt = $conn->prepare($sql);

        // Bind parameters
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image_url', $image_url);
        $stmt->bindParam(':stock_quantity', $stock_quantity);

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
