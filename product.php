<?php
include 'navbar.php';
include 'db.php';

// Fetch product data with category names and descriptions
$sqlProducts = "
    SELECT p.id, p.name, p.price, p.brand_id, p.stock, p.description, c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category = c.category_id
";
$resultProducts = $mysqli->query($sqlProducts);

$tireModels = array();

if ($resultProducts->num_rows > 0) {
    while ($row = $resultProducts->fetch_assoc()) {
        $productId = $row['id'];
        if (!isset($tireModels[$productId])) {
            $tireModels[$productId] = [
                'details' => [
                    'name' => $row['name'],
                    'price' => $row['price'],
                    'stock' => $row['stock'],
                    'category_name' => $row['category_name'],
                    'brand_id' => $row['brand_id'],
                    'description' => $row['description'] // Get the description directly from products table
                ],
                'images' => [] // Initialize an empty array to store image paths
            ];
        }
    }
    $resultProducts->free();
}

// Fetch product images and associate them with products
$sqlImages = "
    SELECT product_id, image_path
    FROM product_images
";
$resultImages = $mysqli->query($sqlImages);

if ($resultImages->num_rows > 0) {
    while ($row = $resultImages->fetch_assoc()) {
        $productId = $row['product_id'];
        if (isset($tireModels[$productId])) {
            $tireModels[$productId]['images'][] = $row['image_path'];
        }
    }
    $resultImages->free();
}

// Fetch unique categories
$sqlCategories = "
    SELECT DISTINCT c.category_name
    FROM products p
    LEFT JOIN categories c ON p.category = c.category_id
";
$resultCategories = $mysqli->query($sqlCategories);
$categories = [];
if ($resultCategories->num_rows > 0) {
    while ($row = $resultCategories->fetch_assoc()) {
        $categories[] = $row['category_name'];
    }
    $resultCategories->free();
}

// Fetch unique brands
$sqlBrands = "
    SELECT DISTINCT b.brand_name
    FROM products p
    LEFT JOIN brands b ON p.brand_id = b.brand_id
";
$resultBrands = $mysqli->query($sqlBrands);
$brands = [];
if ($resultBrands->num_rows > 0) {
    while ($row = $resultBrands->fetch_assoc()) {
        $brands[] = $row['brand_name'];
    }
    $resultBrands->free();
}

$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="product.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />
    <title>Product</title>
</head>
<body>
    <div class="container promo-container px-5 py-3 mt-4"> 
        <div class="row">
            <div class="col-md-8 d-flex-column align-self-center">
                <h1>Drive with Confidence—Quality Tires for Every Journey.</h1>
                <p>Our tire products are meticulously engineered to deliver unparalleled performance, safety, and durability, ensuring a smooth, reliable, and confident driving experience in all weather conditions and on any type of road. </p>
                <p class="lead">The piece standout for their contemporary lines and imposing presence</p>
            </div>
            <div class="col-md-4">
                <img src="images/tire.jpg" alt="" />
            </div>
        </div>
    </div>

    <div class="feature-product-container container mt-4" id="container">
        <div class="row">
            <div class="col-md-2 categories">
                <h2>BRANDS</h2>
                <ul>
                    <?php foreach ($brands as $brand): ?>
                        <li><a href="product_detail.php?brand=<?php echo urlencode($brand); ?>"><?php echo htmlspecialchars($brand); ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div id="product-container" class="product-container col-md-10">
                <?php foreach ($tireModels as $product): ?>
                    <div class="product-container">

                    <?php if (!empty($product['images'])): ?> 
                                <img class="img-fluid" src="admin/uploads/<?php echo htmlspecialchars($product['images'][0]); ?>" alt="Product Image" />
                            <?php else: ?>
                                <img class="img-fluid" src="images/default-product.jpg" alt="Default Product Image" />
                            <?php endif; ?>
                        <p class="product-name mt-1 mb-0"><?php echo htmlspecialchars($product['details']['name']); ?></p>
                        <h4 class="product-price mb-0">₱ <?php echo htmlspecialchars($product['details']['price']); ?></h4>
                        <p class="product-desc mb-1"><?php echo htmlspecialchars($product['details']['description']); ?></p>
                        <p class="product-stock mb-1">Stock: <?php echo htmlspecialchars($product['details']['stock']); ?></p>
                        <p class="category-chip mb-1 text-center">
                            <a href="product_detail.php?category=<?php echo urlencode($product['details']['category_name']); ?>">
                                <?php echo htmlspecialchars($product['details']['category_name']); ?>
                            </a>
                        </p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</body>
</html>
