<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = htmlspecialchars(trim($_POST['name']));
    $category = htmlspecialchars(trim($_POST['category']));
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $description = htmlspecialchars(trim($_POST['description']));
    $attributes = htmlspecialchars(trim($_POST['attributes']));
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

    if (!$price || $price < 0) {
        die("ราคาต้องเป็นตัวเลขที่มากกว่า 0");
    }
    if (!$stock || $stock < 0) {
        die("จำนวนในสต็อกต้องเป็นตัวเลขที่มากกว่า 0");
    }

    $image = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            if (!is_dir('uploads')) {
                mkdir('uploads', 0777, true); // สร้างโฟลเดอร์หากไม่มี
            }
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = 'uploads/' . uniqid() . '.' . $ext;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                die("อัปโหลดภาพไม่สำเร็จ");
            }
        } else {
            die("ไฟล์ภาพต้องเป็นชนิด jpg, png, หรือ gif");
        }
    }

    $conn = new mysqli('localhost', 'root', '', 'shop');
    if ($conn->connect_error) {
        die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("INSERT INTO products (name, category, price, description, image, attributes, stock) VALUES (?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die("คำสั่ง SQL ผิดพลาด: " . $conn->error);
    }
    $stmt->bind_param("ssssssi", $name, $category, $price, $description, $image, $attributes, $stock);

    if ($stmt->execute()) {
        echo "<script>
                alert('สินค้าเพิ่มสำเร็จ!');
                window.location.href = 'manage_products.php';
              </script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<form action="add_product.php" method="post" enctype="multipart/form-data">
    <label for="name">ชื่อสินค้า:</label>
    <input type="text" id="name" name="name" required><br>
    
    <label for="category">หมวดหมู่สินค้า:</label>
    <input type="text" id="category" name="category" required><br>
    
    <label for="price">ราคา:</label>
    <input type="text" id="price" name="price" required><br>
    
    <label for="description">คำอธิบายสินค้า:</label>
    <textarea id="description" name="description" required></textarea><br>
    
    <label for="image">ภาพประกอบสินค้า:</label>
    <input type="file" id="image" name="image"><br>
    
    <label for="attributes">คุณสมบัติเฉพาะ:</label>
    <input type="text" id="attributes" name="attributes"><br>
    
    <label for="stock">จำนวนในสต็อก:</label>
    <input type="number" id="stock" name="stock" required><br>
    
    <input type="submit" value="เพิ่มสินค้า">
</form>
