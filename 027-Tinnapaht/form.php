<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products Management</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
            background-color: #f4f4f9;
        }
        form {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background: #fff;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        label {
            font-weight: bold;
        }
        input, textarea, button {
            width: 100%;
            margin-top: 10px;
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        button {
            background-color: #5cb85c;
            color: white;
            border: none;
            cursor: pointer;
        }
        button:hover {
            background-color: #4cae4c;
        }
    </style>
</head>
<body>

<h2>Product Management Form</h2>
<form action="add_Items.php" method="post">
    <label for="name">Product Name<span style="color: red;">*</span>:</label>
    <input type="text" id="name" name="name" placeholder="Enter product name" required>

    <label for="category">Category:</label>
    <input type="text" id="category" name="category" placeholder="Enter product category">

    <label for="price">Price<span style="color: red;">*</span>:</label>
    <input type="number" step="0.01" id="price" name="price" placeholder="Enter product price" required>

    <label for="descriptions">Description:</label>
    <textarea id="descriptions" name="descriptions" placeholder="Enter product description" rows="4"></textarea>

    <label for="images_url">Image URL:</label>
    <input type="url" id="images_url" name="images_url" placeholder="Enter product image URL">

    <label for="quantity">Stock Quantity<span style="color: red;">*</span>:</label>
    <input type="number" id="quantity" name="quantity" placeholder="Enter stock quantity" required>

    <button type="submit">Add Product</button>
</form>

</body>
</html>
