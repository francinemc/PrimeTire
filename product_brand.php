<?php
// Include database connection
include 'db.php';

$data = $mysqli->real_escape_string($_GET['brand']);

$sqlProducts = "
SELECT p.id, p.name, p.price, p.brand_id, p.stock, p.description, b.brand_name
FROM products p 
INNER JOIN brands b ON p.brand_id = b.brand_id
WHERE b.brand_name = ?
";

$stmt = $mysqli->prepare($sqlProducts);
$stmt->bind_param("s", $data);
$stmt->execute();
$resultProducts = $stmt->get_result();

// Fetch product images and associate them with products
$tireModels = [];
$sqlImages = "
    SELECT product_id, image_path
    FROM product_images
";
$resultImages = $mysqli->query($sqlImages);

if ($resultImages->num_rows > 0) {
    while ($row = $resultImages->fetch_assoc()) {
        $productId = $row['product_id'];
        if (!isset($tireModels[$productId])) {
            $tireModels[$productId] = ['images' => []];
        }
        $tireModels[$productId]['images'][] = $row['image_path'];
    }
    $resultImages->free();
}

// Fetch unique categories
$sqlCategories = "
    SELECT DISTINCT c.category_name
    FROM categories c
    LEFT JOIN products p ON p.category = c.category_id
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
    FROM brands b
    LEFT JOIN products p ON p.brand_id = b.brand_id
";
$resultBrands = $mysqli->query($sqlBrands);
$brands = [];
if ($resultBrands->num_rows > 0) {
    while ($row = $resultBrands->fetch_assoc()) {
        $brands[] = $row['brand_name'];
    }
    $resultBrands->free();
}

$product_id = isset($_GET['id']) ? intval($_GET['id']) : 1;

$product_query = "SELECT * FROM products WHERE id = ?";
$stmt = $mysqli->prepare($product_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

$image_query = "SELECT image_path FROM product_images WHERE product_id = ?";
$stmt = $mysqli->prepare($image_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$image_result = $stmt->get_result();
$images = [];
while ($row = $image_result->fetch_assoc()) {
    $images[] = $row['image_path'];
}

$size_query = "SELECT size_value FROM sizes WHERE size_id = ?";
$stmt = $mysqli->prepare($size_query);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$size_result = $stmt->get_result();
$sizes = [];
while ($row = $size_result->fetch_assoc()) {
    $sizes[] = $row['size_value'];
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Detail</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="products.css">
    <link rel="stylesheet" href="products_bc.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />
</head>


<body>
    <?php include 'navbar.php'; ?>

    <div class="container">
    <hr />
    <p class="lead">Related Products</p>
    <div class="row">
        <?php   
        if ($resultProducts->num_rows > 0) {
            while ($row = $resultProducts->fetch_assoc()) {
                $productImages = isset($tireModels[$row['id']]['images']) ? $tireModels[$row['id']]['images'] : [];
                $productUrl = "product_list.php?id=" . $row['id'];
        ?>
            <div class="col-md-3">
                <div class="product-container">
                    <?php if (!empty($productImages)): ?> 
                        <img class="img-fluid" src="admin/uploads/<?php echo htmlspecialchars($productImages[0]); ?>" alt="Product Image" />
                    <?php else: ?>
                        <img class="img-fluid" src="images/default-product.jpg" alt="Default Product Image" />
                    <?php endif; ?>
                    <h4 class="product-name mt-1 mb-0"><?php echo htmlspecialchars($row['name']); ?></h4>
                    <h5 class="product-price mb-0"> ₱ <?php echo htmlspecialchars($row['price']); ?></h5>
                    <p class="product-desc mb-1"><?php echo htmlspecialchars($row['description']); ?></p>
                    <a href="product_list.php?id=<?php echo $row['id']; ?>" class="btn-details">Details</a>
                </div>
            </div>
        <?php 
            } 
        } else {
            echo "<p>No related products found.</p>";
        }
        ?>
    </div>
</div>

    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const buyNowButtons = document.querySelectorAll(".buy-now");
        buyNowButtons.forEach(button => {
            button.addEventListener("click", function () {
                const productId = this.dataset.productId;
                const url = `cart.php?add_to_cart=1&id=${productId}&quantity=1`;
                fetch(url)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert("Product added to cart!");
                        } else {
                            alert("Failed to add product to cart.");
                        }
                    })
                    .catch(error => {
                        console.error("Error adding product to cart:", error);
                        alert("An error occurred while adding the product to the cart.");
                    });
            });
        });
    });
    </script>

</body>
</html>
