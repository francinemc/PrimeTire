<?php 
include 'db.php';

$data = $mysqli->real_escape_string($_GET['id']);

// Fetch product details
$sqlProducts = "SELECT * FROM products WHERE id='$data'";
$resultProducts = $mysqli->query($sqlProducts);

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Detail</title>
    <link rel="stylesheet" href="css/bootstrap.min.css" />
    <link rel="stylesheet" href="products_bc.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" />

    <style>
        * {
            font-family: Arial, Helvetica, sans-serif;
            color: #fff; /* Default text color for white text */
        }
        body {
            background-color: #000000cc;
        }
        .primary-img {
            object-fit: cover;
            width: 100%;
        }
        .side-pic {
            margin-bottom: 4px;
            width: 100%;
        }
        .buy-now, .add-to-cart {
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
        }
        .buy-now {
            background-color: orange;
            color: white;
            margin-right: 10px;
        }
        .add-to-cart {
            background-color: white;
            border: 1px solid orange;
            color: orange;
        }
        .size-btn {
            font-size: 14px;
            text-align: center;
            color: black;
            background-color: rgb(250, 216, 152);
            padding: 8px 16px;
            margin: 5px;
            border: 1px solid orange;
            border-radius: 4px;
            cursor: pointer;
        }
        .size-btn.active {
            background-color: orange;
            color: white;
        }
        .quantity-container {
            display: flex;
            margin-bottom: 15px;
        }
        .quantity-container button {
            border: none;
            background-color: lightgray;
            padding: 10px;
            cursor: pointer;
            color:black;
        }
        .quantity-container input {
            border: 1px solid orange;
            text-align: center;
            width: 60px;
            color: black; /* Set quantity number color to black */
        }
        @media only screen and (max-width: 759px) {
            .big-side-pic {
                display: none;
            }
            .small-side-pic {
                display: grid;
                grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
                margin: 4px 0;
            }
            .side-pic {
                width: 100%;
                object-fit: cover;
            }
        }
        .product-details {
            padding-left: 20px;
        }
        .product-container {
            background-color: black;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .btn-details {
            background-color: orange;
            color: white;
            border: none;
            border-radius: 4px;
            padding: 8px 16px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        .btn-details:hover {
            background-color: darkorange;
        }
        hr {
            border-color: #ff5722; /* Orange for horizontal rule */
            border-width: 2px;
            margin-top: 30px;
        }
        .add-to-cart {
            color: black; /* Set checkout text color to black */
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>

    <?php if ($resultProducts->num_rows > 0) { 
        $row = $resultProducts->fetch_assoc(); ?>

    <div class="container py-4 px-0">
        <div class="row">
            <div class="col-md-7">
                <div class="row">
                    <div class="big-side-pic col-md-3" id="side-tire">
                        <?php 
                        $product_id = $row['id'];
                        $sqlProducts = "SELECT * FROM product_images WHERE product_id='$product_id' LIMIT 2";
                        $thumbnailProducts = $mysqli->query($sqlProducts);

                        if ($thumbnailProducts->num_rows > 0) {
                            while ($thumbnailImage = $thumbnailProducts->fetch_assoc()) { ?>
                                <img class="side-pic img-fluid" src="admin/uploads/<?php echo htmlspecialchars($thumbnailImage['image_path']); ?>" alt="Product Thumbnail" onerror="this.onerror=null;this.src='images/default-product.jpg';" />
                            <?php } 
                        } ?>
                    </div>
                    <div class="col-md-9">
                        <?php 
                        $sqlProducts = "SELECT * FROM product_images WHERE product_id='$product_id' LIMIT 1";
                        $imageProduct = $mysqli->query($sqlProducts);
                        if ($imageProduct->num_rows > 0) {
                            $imageRow = $imageProduct->fetch_assoc(); ?>
                            <img class="primary-img img-fluid" src="admin/uploads/<?php echo htmlspecialchars($imageRow['image_path']); ?>" alt="Main Product Image" onerror="this.onerror=null;this.src='images/default-product.jpg';" />
                        <?php } else { ?>
                            <img class="primary-img img-fluid" src="images/default-product.jpg" alt="Default Product Image" />
                        <?php } ?>
                    </div>
                </div>
            </div>
            <div class="col-md-5 product-details">
                <h2><?php echo htmlspecialchars($row['name']);?></h2>
                <p><?php echo htmlspecialchars($row['description']); ?></p>
                <div>
                    <span class="fa fa-star checked" style="color: yellow"></span>
                    <span class="fa fa-star checked" style="color: yellow"></span>
                    <span class="fa fa-star checked" style="color: yellow"></span>
                    <span class="fa fa-star"></span>
                    <span class="fa fa-star"></span>
                    <span class="ml-1">120+ Reviews</span>
                </div>
                <h5 class="pb-1" id="price-lead">₱<span id="price"><?php echo htmlspecialchars($row['price']); ?></span></h5>
                <h4 class="pb-3 pt-0 mt-0" id="total-lead">₱<span id="total">₱<?php echo htmlspecialchars($row['price']); ?></span></h4>
                
                <div class="quantity-container">
                    <button class="subtract" id="subtract" onclick="subtract()">-</button>
                    <input type="number" name="quantity" id="quantity" min="1" value="1" />
                    <button class="add" id="add" onclick="add()">+</button>
                </div>

                <p class="mb-1 p-0 ml-1">Sizes:</p>
                <div class="row pb-4 ml-0" id="sizes-container">
                    <?php 
                    $sqlSizes = "
                    SELECT ps.*, s.size_value 
                    FROM product_sizes ps
                    INNER JOIN sizes s ON ps.size_id = s.size_id
                    WHERE ps.id = '$product_id'
                    ";
                    $sizes = $mysqli->query($sqlSizes);

                    if ($sizes->num_rows > 0) {
                        while ($sizeRow = $sizes->fetch_assoc()) { ?>
                            <button class="size-btn" data-size-id="<?php echo htmlspecialchars($sizeRow['size_id']); ?>"><?php echo htmlspecialchars($sizeRow['size_value']); ?></button>
                        <?php } 
                    } ?>
                </div>
                <div class="row">
                 <a href="cart.php" class="buy-now col-sm-5 mr-3 btn btn-warning">Add to Cart</a>
                 <a href="checkout.php" class="add-to-cart col-sm-5 btn btn-info">Checkout</a>
               </div>

        </div>
        <div class="container">
        <hr /><br>
        <p class="lead">Related Products</p>
        <div class="row">
            <?php   
            // Fetch related products
            $sqlRelatedProducts = "
            SELECT * FROM products
            WHERE id != '$data'
            LIMIT 4
            ";
            $relatedProducts = $mysqli->query($sqlRelatedProducts);

            if ($relatedProducts->num_rows > 0) {
                while ($row = $relatedProducts->fetch_assoc()) {
                    $sqlRelatedImages = "SELECT image_path FROM product_images WHERE product_id='" . $row['id'] . "' LIMIT 1";
                    $relatedImages = $mysqli->query($sqlRelatedImages);
                    $relatedImage = $relatedImages->fetch_assoc();
                    $productImage = isset($relatedImage['image_path']) ? $relatedImage['image_path'] : 'images/default-product.jpg';
            ?>
                <div class="col-md-3">
                    <div class="product-container">
                        <img class="img-fluid" src="admin/uploads/<?php echo htmlspecialchars($productImage); ?>" alt="Related Product Image" onerror="this.onerror=null;this.src='images/default-product.jpg';" />
                        <h4 class="product-name"><?php echo htmlspecialchars($row['name']); ?></h4>
                        <h5 class="product-price mb-0">₱ <?php echo htmlspecialchars($row['price']); ?></h5>
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
    <?php } ?> 

    <script>
        document.querySelectorAll('.size-btn').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.size-btn').forEach(btn => btn.classList.remove('active'));
                this.classList.add('active');
            });
        });

        document.querySelector('.buy-now').addEventListener('click', function () {
            const size = document.querySelector('.size-btn.active').textContent;
            const quantity = document.getElementById('quantity').value;
            const price = document.getElementById('price').textContent;
            const productName = document.querySelector('h2').textContent;

            const formData = new FormData();
            formData.append('action', 'add_to_cart');
            formData.append('name', productName);
            formData.append('quantity', quantity);
            formData.append('price', price);
            formData.append('size', size);

            fetch('cart_handler.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                window.location.href = 'cart.php'; // Redirect to cart page
            })
            .catch(error => {
                console.error('Error:', error);
            });
        });

        function add() {
            const input = document.getElementById('quantity');
            const currentValue = parseInt(input.value, 10);
            const price = parseFloat(document.getElementById('price').textContent);
            input.value = currentValue + 1;
            updateTotal();
        }

        function subtract() {
            const input = document.getElementById('quantity');
            if (input.value > 1) {
                input.value = parseInt(input.value, 10) - 1;
                updateTotal();
            }
        }

        function updateTotal() {
            const quantity = parseInt(document.getElementById('quantity').value, 10);
            const price = parseFloat(document.getElementById('price').textContent);
            const total = quantity * price;
            document.getElementById('total').textContent = total.toFixed(2);
        }
        
        // Initial total calculation
        updateTotal();
    </script>
</body>
</html>
