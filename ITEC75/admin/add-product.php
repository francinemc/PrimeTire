<?php
// Include the database connection file
include '../db.php'; // Adjust path as necessary

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $productName = $_POST['product_name'];
    $category = $_POST['category'];
    $brand = $_POST['brand'];
    $description = $_POST['description'];
    $size = $_POST['size']; // Array of selected sizes
    $stock = $_POST['stock'];
    
    // Initialize an array to hold image paths
    $imagePaths = [];

    // Handle image uploads
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] == UPLOAD_ERR_OK) {
        $uploadDir = __DIR__ . '/uploads/';
        
        // Ensure the uploads directory exists
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $imageName = $_FILES['images']['name'][$key];
            $imagePath = $uploadDir . basename($imageName);

            // Move the uploaded file to the desired directory
            if (move_uploaded_file($tmpName, $imagePath)) {
                $imagePaths[] = $imagePath;
            } else {
                $message = "Failed to upload image: $imageName";
                break;
            }
        }

        if (empty($message)) {
            // Prepare SQL statement to insert product data
            $stmt = $mysqli->prepare("INSERT INTO products (product_name, category_id, brand, description, stock) VALUES (?, ?, ?, ?, ?)");
            
            if ($stmt) {
                // Bind parameters
                $stmt->bind_param('ssssi', $productName, $category, $brand, $description, $stock);
                
                // Execute the statement
                if ($stmt->execute()) {
                    $productId = $stmt->insert_id;
                    $stmt->close();

                    // Insert sizes into the product_sizes table
                    $stmt = $mysqli->prepare("INSERT INTO sizes (product_id, size_id) VALUES (?, ?)");
                    
                    if ($stmt) {
                        foreach ($sizes as $sizeId) {
                            $stmt->bind_param('ii', $productId, $sizeId);
                            $stmt->execute();
                        }
                        $stmt->close();
                    }

                    // Insert image paths into the database
                    $stmt = $mysqli->prepare("INSERT INTO product_images (product_id, image_path) VALUES (?, ?)");
                    
                    if ($stmt) {
                        foreach ($imagePaths as $path) {
                            $stmt->bind_param('is', $productId, $path);
                            $stmt->execute();
                        }
                        $stmt->close();
                        $message = "Product added successfully with images.";
                    } else {
                        $message = "Failed to prepare image SQL statement.";
                    }
                } else {
                    $message = "Failed to add product.";
                }
            } else {
                $message = "Failed to prepare the SQL statement.";
            }
        }
    } else {
        $message = "No images uploaded or upload error.";
    }
}

// Fetch categories and sizes for dropdowns
$categories = [];
$sizes = [];

try {
    // Fetch categories
    $result = $mysqli->query("SELECT category_id, category_name FROM categories");
    if ($result) {
        $categories = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }

    // Fetch sizes
    $result = $mysqli->query("SELECT size_id, size_value FROM sizes");
    if ($result) {
        $sizes = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();
    }
} catch (mysqli_sql_exception $e) {
    $message = "Database error: " . $e->getMessage();
}

// Close the database connection
$mysqli->close();
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <link rel="stylesheet" href="add-product.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: beige;
            margin: 0;
            padding: 0;
        }

        .dashboard {
            display: flex;
        }

        .sidebar {
            width: 250px;
            background-color: #121416; /* Color 3 */
            padding: 20px;
            min-height: 100vh;
        }

        .sidebar h2 {
            color: #efc143; /* Color 1 */
            font-size: 22px;
            text-align: center;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            margin: 20px 0;
        }

        .sidebar ul li a {
            color: #ffffff;
            text-decoration: none;
            font-size: 18px;
            display: block;
            padding: 10px;
            transition: 0.3s;
        }

        .sidebar ul li a:hover {
            background-color: #eb7324; /* Color 2 */
            border-radius: 5px;
        }

        .main-content {
            flex-grow: 1;
            padding: 20px;
        }

        .main-content header {
            background-color: #efc143; /* Color 1 */
            padding: 20px;
            border-bottom: 1px solid #e1e1e1;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .main-content h2 {
            margin-top: 0;
            color: #121416; /* Color 3 */
        }
        
        
        .main-content input[type="text"],
        .main-content textarea,
        .main-content select,
        .main-content input[type="file"],
        .main-content input[type="number"] {
            padding: 10px;
            border: 1px solid #e1e1e1;
            border-radius: 5px;
            width: 100%;
            margin-bottom: 20px;
        }

        .main-content form button:hover {
            background-color: #d15d00; /* Slightly darker shade of Color 2 */
        }

        .message {
            color: #d15d00; /* Error color */
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="dashboard">
        <nav class="sidebar">
            <h2>Prime Tire's Dashboard</h2>
            <ul class="menu">
                <li><a href="admin.php">Dashboard</a></li>
                <li><a href="products.php">Products</a></li>
                <li><a href="orders.php">Orders</a></li>
                <li><a href="add-category.php">Add Category</a></li>
                <li><a href="add-product.php">Add Product</a></li>
                <li><a href="settings.php">Settings</a></li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

        <div class="main-content">
            <header>
                <input type="text" placeholder="Search...">
                <div class="user-info">
                    <span>Administrator</span>
                </div>
            </header>
            <br>
            <h2>Add Product</h2>
            <?php if (!empty($message)): ?>
                <div class="message"><?php echo htmlspecialchars($message); ?></div>
            <?php endif; ?>
            <form action="add-product.php" method="POST" enctype="multipart/form-data">
    <label for="product-name">Product Name:</label>
    <input type="text" id="product-name" name="product_name" required>

    <label for="category">Category:</label>
    <select id="category" name="category" required>
        <?php foreach ($categories as $cat): ?>
            <option value="<?php echo htmlspecialchars($cat['category_id']); ?>">
                <?php echo htmlspecialchars($cat['category_name']); ?>
            </option>
        <?php endforeach; ?>
    </select>

    <label for="brand">Brand:</label>
    <input type="text" id="brand" name="brand" required>

    <label for="description">Description:</label>
    <textarea id="description" name="description" required></textarea>

    <label for="size">Size:</label><br>
    <?php foreach ($sizes as $sz): ?>
        <input type="checkbox" id="size-<?php echo $sz['size_id']; ?>" name="sizes[]" value="<?php echo htmlspecialchars($sz['size_id']); ?>">
        <label for="size-<?php echo $sz['size_id']; ?>"><?php echo htmlspecialchars($sz['size_value']); ?></label><br>
    <?php endforeach; ?>

    <label for="stock">Stock:</label>
    <input type="number" id="stock" name="stock" required>

    <label for="images">Images:</label>
    <input type="file" id="images" name="images[]" accept="image/*" multiple required>

    <button type="submit">Add Product</button>
</form>

 

        </div>
    </div>
</body>
</html>
