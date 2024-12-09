<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>จัดการสินค้า</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }
        .form-container, .card-container {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            margin-bottom: 20px;
        }
        .form-container h2, .card-container h2 {
            margin-top: 0;
        }
        .form-container form {
            display: grid;
            grid-template-columns: auto 1fr;
            gap: 10px;
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
        .form-container input[type="submit"] {
            grid-column: 1 / 3;
            padding: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        .form-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .card-container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
        }
        .card {
            border: 1px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            width: 300px;
            background-color: white;
        }
        .card img {
            width: 100%;
        }
        .card-content {
            padding: 16px;
        }
        .card-content h3 {
            margin: 0 0 10px 0;
        }
        .card-content p {
            margin: 5px 0;
        }
        .card-actions {
            padding: 16px;
            display: flex;
            justify-content: space-between;
        }
        .card-actions a {
            text-decoration: none;
            color: white;
            padding: 8px 16px;
            border-radius: 4px;
        }
        .card-actions a.edit {
            background-color: #ffc107; /* สีเหลือง */
        }
        .card-actions a.delete {
            background-color: #dc3545; /* สีแดง */
        }
        .card-actions a:hover.edit {
            background-color: #e0a800; /* สีเหลืองเข้มขึ้น */
        }
        .card-actions a:hover.delete {
            background-color: #c82333; /* สีแดงเข้มขึ้น */
        }
    </style>
    <script>
        function validateForm() {
            var name = document.getElementById("name").value;
            var category = document.getElementById("category").value;
            var price = document.getElementById("price").value;
            var description = document.getElementById("description").value;
            var stock = document.getElementById("stock").value;
            
            if (name == "" || category == "" || price == "" || description == "" || stock == "") {
                alert("กรุณากรอกข้อมูลให้ครบทุกฟิลด์");
                return false;
            }
            return true;
        }
    </script>
</head>
<body>
    <h1>จัดการสินค้า</h1>
    
    <div class="form-container">
        <h2>เพิ่มสินค้า</h2>
        <form action="add_product.php" method="post" enctype="multipart/form-data" onsubmit="return validateForm()">
            <label for="name">ชื่อสินค้า:</label>
            <input type="text" id="name" name="name" required>
            
            <label for="category">หมวดหมู่สินค้า:</label>
            <input type="text" id="category" name="category" required>
            
            <label for="price">ราคา:</label>
            <input type="text" id="price" name="price" required>
            
            <label for="description">คำอธิบายสินค้า:</label>
            <textarea id="description" name="description" required></textarea>
            
            <label for="image">ภาพประกอบสินค้า:</label>
            <input type="file" id="image" name="image">
            
            <label for="attributes">คุณสมบัติเฉพาะ:</label>
            <input type="text" id="attributes" name="attributes">
            
            <label for="stock">จำนวนในสต็อก:</label>
            <input type="number" id="stock" name="stock" required>
            
            <input type="submit" value="เพิ่มสินค้า">
        </form>
    </div>

    <div class="form-container">
        <h2>รายการสินค้า</h2>
        <div class="card-container">
            <?php
            $conn = new mysqli('localhost', 'root', '', 'shop');
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }
            
            $result = $conn->query("SELECT * FROM products");
            
            while ($row = $result->fetch_assoc()) {
                echo "<div class='card'>";
                echo "<img src='" . $row['image'] . "' alt='Product Image'>";
                echo "<div class='card-content'>";
                echo "<h3>" . $row['name'] . "</h3>";
                echo "<p>หมวดหมู่: " . $row['category'] . "</p>";
                echo "<p>ราคา: " . $row['price'] . "</p>";
                echo "<p>" . $row['description'] . "</p>";
                echo "<p>คุณสมบัติเฉพาะ: " . $row['attributes'] . "</p>";
                echo "<p>จำนวนในสต็อก: " . $row['stock'] . "</p>";
                echo "</div>";
                echo "<div class='card-actions'>";
                echo "<a href='edit_product.php?id=" . $row['id'] . "' class='edit'>แก้ไข</a>";
                echo "<a href='delete_product.php?id=" . $row['id'] . "' class='delete'>ลบ</a>";
                echo "</div>";
                echo "</div>";
            }
            
            $conn->close();
            ?>
        </div>
    </div>

</body>
</html>
