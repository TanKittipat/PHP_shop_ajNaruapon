<?php
// เชื่อมต่อฐานข้อมูล
$conn = new mysqli('localhost', 'root', '', 'shop');
if ($conn->connect_error) {
    die("การเชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// ตรวจสอบและรับค่า id จาก URL
if (isset($_GET['id'])) {
    if (!filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
        echo "<script>alert('ID ไม่ถูกต้อง (ต้องเป็นตัวเลขเท่านั้น)'); window.location.href = 'product_list.php';</script>";
        exit;
    }
    $id = $_GET['id'];
} else {
    echo "<script>alert('ไม่ได้ระบุ ID'); window.location.href = 'product_list.php';</script>";
    exit;
}

// ดึงข้อมูลสินค้าจากฐานข้อมูล
$stmt = $conn->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $product = $result->fetch_assoc();
} else {
    echo "<script>alert('ไม่พบสินค้าที่ระบุ'); window.location.href = 'product_list.php';</script>";
    exit;
}

// เมื่อผู้ใช้งานส่งฟอร์ม
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = filter_var($_POST['id'], FILTER_VALIDATE_INT);
    $name = htmlspecialchars(trim($_POST['name']));
    $category = htmlspecialchars(trim($_POST['category']));
    $price = filter_var($_POST['price'], FILTER_VALIDATE_FLOAT);
    $description = htmlspecialchars(trim($_POST['description']));
    $attributes = htmlspecialchars(trim($_POST['attributes']));
    $stock = filter_var($_POST['stock'], FILTER_VALIDATE_INT);

    if (!$id || !$price || $price < 0 || !$stock || $stock < 0) {
        die("ข้อมูลไม่ถูกต้อง");
    }

    // จัดการรูปภาพ
    $image = $_POST['current_image'] ?? '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
        if (in_array($_FILES['image']['type'], $allowed_types)) {
            $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
            $image = 'uploads/' . uniqid() . '.' . $ext;
            if (!move_uploaded_file($_FILES['image']['tmp_name'], $image)) {
                die("อัปโหลดภาพไม่สำเร็จ");
            }
        } else {
            die("ไฟล์ภาพต้องเป็นชนิด jpg, png, หรือ gif");
        }
    }

    // อัปเดตข้อมูลในฐานข้อมูล
    $stmt = $conn->prepare("UPDATE products SET name=?, category=?, price=?, description=?, image=?, attributes=?, stock=? WHERE id=?");
    if (!$stmt) {
        die("คำสั่ง SQL ผิดพลาด: " . $conn->error);
    }
    $stmt->bind_param("ssdssssi", $name, $category, $price, $description, $image, $attributes, $stock, $id);

    if ($stmt->execute()) {
        echo "<script>alert('สินค้าถูกแก้ไขสำเร็จ!'); window.location.href = 'manage_products.php';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $stmt->error;
    }

    $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แก้ไขสินค้า</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #e6f7ff;
        }
        .form-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            max-width: 600px;
            margin: auto;
        }
        .form-container h2 {
            margin-top: 0;
            color: #0056b3;
        }
        .form-container form {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px 20px;
        }
        .form-container label {
            display: block;
            align-self: center;
        }
        .form-container input[type="text"],
        .form-container input[type="file"],
        .form-container input[type="number"],
        .form-container textarea {
            width: 100%;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .form-container button {
            grid-column: 1 / 3;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }
        .form-container button:hover {
            background-color: #0056b3;
        }
        .form-container .image-preview {
            grid-column: 1 / 3;
            text-align: center;
        }
        .form-container img {
            max-width: 30%;
            border-radius: 4px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-top: 10px;
        }
    
    </style>
</head>
<body>
    <div class="form-container">
        <h2>แก้ไขสินค้า</h2>
        <form action="edit_product.php?id=<?php echo $product['id']; ?>" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $product['id']; ?>">
            <input type="hidden" name="current_image" value="<?php echo $product['image']; ?>">

            <label for="name">ชื่อสินค้า:</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($product['name']); ?>" required>
            
            <label for="category">หมวดหมู่สินค้า:</label>
            <input type="text" id="category" name="category" value="<?php echo htmlspecialchars($product['category']); ?>" required>
            
            <label for="price">ราคา:</label>
            <input type="text" id="price" name="price" value="<?php echo htmlspecialchars($product['price']); ?>" required>
            
            <label for="description">คำอธิบายสินค้า:</label>
            <textarea id="description" name="description" required><?php echo htmlspecialchars($product['description']); ?></textarea>
            
            <label for="image">ภาพประกอบสินค้า:</label>
            <input type="file" id="image" name="image">
            <div class="image-preview">
                <?php if (!empty($product['image'])): ?>
                    <img class="image-preview" src="<?php echo $product['image']; ?>" alt="ภาพสินค้า">
                <?php endif; ?>
            </div>

            <label for="attributes">คุณสมบัติเฉพาะ:</label>
            <input type="text" id="attributes" name="attributes" value="<?php echo htmlspecialchars($product['attributes']); ?>">
            
            <label for="stock">จำนวนในสต็อก:</label>
            <input type="number" id="stock" name="stock" value="<?php echo htmlspecialchars($product['stock']); ?>" required>
            
            <button type="submit">บันทึกการแก้ไข</button>
        </form>
    </div>
</body>
</html>
