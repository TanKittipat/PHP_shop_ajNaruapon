<?php
require "db.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $itemName = $_POST['item-name'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';
    $itemImage = $_POST['item-image'] ?? '';
    $specificFeature = $_POST['specific'] ?? '';
    $instock = $_POST['instock'] ?? 0;

    if (empty($itemName) || empty($category) || empty($price) || empty($description) || empty($itemImage) || empty($specificFeature) || empty($instock)) {
        echo "Please fill out all required fields.";
        exit;
    }

    try {
        // Prepare the SQL query to insert data
        $sql = "INSERT INTO items (name, category, price, description, image, specific_feature, instock) 
                VALUES (:name, :category, :price, :description, :image, :specific_feature, :instock)";

        $stmt = $conn->prepare($sql);

        // Bind values to parameters
        $stmt->bindParam(':name', $itemName);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $itemImage);
        $stmt->bindParam(':specific_feature', $specificFeature);
        $stmt->bindParam(':instock', $instock);

        // Execute the query
        if ($stmt->execute()) {
            echo "Item added successfully!";
        } else {
            echo "Error adding item.";
        }
    } catch (PDOException $e) {
        // Handle any errors with the database
        echo "Error: " . $e->getMessage();
    }
} else {
    echo "Invalid request.";
}
