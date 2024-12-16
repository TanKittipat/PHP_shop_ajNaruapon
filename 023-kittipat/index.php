<?php
require "db.php";

$sql = "SELECT * FROM items";
$stmt = $conn->prepare($sql);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Store management</title>
</head>

<body class="bg-gray-100">
    <div class="container mx-auto px-4 py-10">
        <h3 class="text-3xl font-bold py-2 text-center">Store items</h3>
        <div class="py-4 flex justify-end">
            <a href="add.php" class="rounded-md bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600">Add new item</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-8">
            <?php
            while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
            ?>
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <img src="<?php echo htmlspecialchars($result['image']); ?>" alt="<?php echo htmlspecialchars($result['name']); ?>" class="w-full h-48 object-cover">
                    <div class="p-4">
                        <h2 class="text-md font-semibold text-gray-800"><?php echo htmlspecialchars($result['name']); ?></h2>
                        <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($result['description']); ?></p>
                        <p class="text-gray-600 text-sm">จำนวนสินค้า: <?php echo htmlspecialchars($result['instock']); ?></p>

                        <p class="text-sm font-semibold text-gray-900 mt-2">ราคา: <?php echo number_format($result['price'], 2); ?>฿</p>
                        <div class="gap-2 pt-2 flex justify-end">
                            <a href="edit.php?id=<?php echo $result['id']; ?>" class="rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Edit</a>
                            <a href="delete.php?id=<?php echo $result['id']; ?>" class="rounded-md bg-red-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-red-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600">Delete</a>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>

</body>

</html>