<?php
require "db.php";

// Check if item ID is provided in the URL
$itemId = isset($_GET['id']) ? $_GET['id'] : null;
if ($itemId) {
    // Fetch the existing data for the item to populate the form
    $stmt = $conn->prepare("SELECT * FROM items WHERE id = :id");
    $stmt->bindParam(':id', $itemId);
    $stmt->execute();
    $item = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$item) {
        echo "Item not found.";
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="script.js"></script>
    <title>Store Management - Edit Item</title>
</head>

<body>
    <div class="container mx-auto px-24 py-10">
        <h1 class="text-xl font-bold">Form for Editing an Item</h1>
        <form action="edit.php?id=<?php echo $itemId; ?>" method="post" id="editing-form" class="mt-5 grid grid-cols-1 gap-x-6 gap-y-4 sm:grid-cols-6">
            <!-- Item name -->
            <div class="sm:col-span-4">
                <label for="item-name" class="block text-sm/6 font-medium text-gray-900">Item name</label>
                <div class="mt-2">
                    <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                        <input type="text" name="item-name" id="item-name" class="block min-w-0 grow py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6" placeholder="Item name" value="<?php echo htmlspecialchars($item['name']); ?>">
                    </div>
                </div>
            </div>
            <!-- Category -->
            <div class="sm:col-span-3">
                <label for="category" class="block text-sm/6 font-medium text-gray-900">Category</label>
                <div class="mt-2 grid grid-cols-1">
                    <select id="category" name="category" class="col-start-1 row-start-1 w-full appearance-none rounded-md bg-white py-1.5 pl-3 pr-8 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6">
                        <?php
                        $stmt = $conn->prepare("SELECT * FROM categories");
                        $stmt->execute();

                        while ($result = $stmt->fetch(PDO::FETCH_ASSOC)) {
                            $selected = ($result['id'] == $item['category']) ? 'selected' : '';
                            echo "<option value={$result['id']} $selected>" . $result['name'] . "</option>";
                        }
                        ?>
                    </select>
                    <svg class="pointer-events-none col-start-1 row-start-1 mr-2 size-5 self-center justify-self-end text-gray-500 sm:size-4" viewBox="0 0 16 16" fill="currentColor" aria-hidden="true" data-slot="icon">
                        <path fill-rule="evenodd" d="M4.22 6.22a.75.75 0 0 1 1.06 0L8 8.94l2.72-2.72a.75.75 0 1 1 1.06 1.06l-3.25 3.25a.75.75 0 0 1-1.06 0L4.22 7.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                    </svg>
                </div>
            </div>
            <!-- Price -->
            <div class="sm:col-span-4">
                <label for="price" class="block text-sm/6 font-medium text-gray-900">Price</label>
                <div class="mt-2">
                    <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                        <input type="number" name="price" id="price" class="block min-w-0 grow py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6" placeholder="Price" value="<?php echo htmlspecialchars($item['price']); ?>">
                    </div>
                </div>
            </div>
            <!-- Description -->
            <div class="col-span-full">
                <label for="description" class="block text-sm/6 font-medium text-gray-900">Description</label>
                <div class="mt-2">
                    <textarea name="description" id="description" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"><?php echo htmlspecialchars($item['description']); ?></textarea>
                </div>
            </div>
            <!-- Image -->
            <div class="sm:col-span-4">
                <label for="item-image" class="block text-sm/6 font-medium text-gray-900">Item image</label>
                <div class="mt-2">
                    <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                        <input type="text" name="item-image" id="item-image" class="block min-w-0 grow py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6" placeholder="Item image" value="<?php echo htmlspecialchars($item['image']); ?>">
                    </div>
                </div>
            </div>
            <!-- Specific feature -->
            <div class="col-span-full">
                <label for="specific" class="block text-sm/6 font-medium text-gray-900">Specific feature</label>
                <div class="mt-2">
                    <textarea name="specific" id="specific" rows="3" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm/6"><?php echo htmlspecialchars($item['specific_feature']); ?></textarea>
                </div>
            </div>
            <!-- Instock -->
            <div class="sm:col-span-4">
                <label for="instock" class="block text-sm/6 font-medium text-gray-900">Instock</label>
                <div class="mt-2">
                    <div class="flex items-center rounded-md bg-white pl-3 outline outline-1 -outline-offset-1 outline-gray-300 focus-within:outline focus-within:outline-2 focus-within:-outline-offset-2 focus-within:outline-indigo-600">
                        <input type="number" name="instock" id="instock" class="block min-w-0 grow py-1.5 pl-1 pr-3 text-base text-gray-900 placeholder:text-gray-400 focus:outline focus:outline-0 sm:text-sm/6" placeholder="Instock" value="<?php echo htmlspecialchars($item['instock']); ?>">
                    </div>
                </div>
            </div>
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="index.php" type="button" onclick="clearForm('editing-form')" class="text-sm/6 font-semibold text-gray-900">Cancel</a>
                <button type="submit" class="rounded-md bg-orange-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-orange-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-orange-600">Save</button>
            </div>
        </form>
    </div>
</body>

</html>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the updated values from the form
    $itemName = $_POST['item-name'] ?? '';
    $category = $_POST['category'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';
    $itemImage = $_POST['item-image'] ?? '';
    $specificFeature = $_POST['specific'] ?? '';
    $instock = $_POST['instock'] ?? 0;

    // Validate the input
    if (empty($itemName) || empty($category) || empty($price) || empty($description) || empty($itemImage) || empty($specificFeature) || empty($instock)) {
        echo "Please fill out all required fields.";
        exit;
    }

    try {
        // Prepare the SQL query to update the item
        $sql = "UPDATE items SET name = :name, category = :category, description = :description, price = :price, image = :image, specific_feature = :specific_feature, instock = :instock WHERE id = :id";

        $stmt = $conn->prepare($sql);

        // Bind the values to the parameters
        $stmt->bindParam(':name', $itemName);
        $stmt->bindParam(':category', $category);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':image', $itemImage);
        $stmt->bindParam(':specific_feature', $specificFeature);
        $stmt->bindParam(':instock', $instock);
        $stmt->bindParam(':id', $itemId);

        // Execute the query
        if ($stmt->execute()) {
            echo "<script>window.alert('Item updated successfully!')
            window.location.href = 'index.php'</script>";
        } else {
            echo "<script>window.alert('Error updating item.')</script>";
        }
    } catch (PDOException $e) {
        echo "<script>window.alert('Error: " . $e->getMessage() . "')</script>";
    }
}
?>