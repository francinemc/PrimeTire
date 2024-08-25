<?php
include 'db.php'; // Ensure this file creates a $mysqli connection variable

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
                    'description' => $row['description']
                ],
                'images' => []
            ];
        }
    }
    $resultProducts->free();
}

// Fetch cart data from the database
$sqlCart = "SELECT p.name AS product_name, p.price, c.quantity, c.product_id, p.image_url
            FROM carts c
            JOIN products p ON c.product_id = p.id";
$resultCart = $mysqli->query($sqlCart);

// Sanitize and fetch product details for a specific ID
$data = $mysqli->real_escape_string($_GET['id']);
$sqlProductDetails = "SELECT * FROM products WHERE id='$data'";
$resultProductDetails = $mysqli->query($sqlProductDetails);

// Initialize totals
$total_price = 0;
$shipping = 35; // Example shipping cost
$tax = 0;       // Example tax

// Calculate totals
$final_total = $total_price + $shipping + $tax;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Detail</title>
    <link rel="stylesheet" href="check.css">
</head>
<body>
<?php include 'navbar.php'; ?>

<div class="info-section">
    <div class="info-item">
        <span class="icon-img">1</span>
        <p>Shopping cart</p>
    </div>
    <div class="info-item">
        <span class="icon-img">2</span>
        <p>Checkout</p>
    </div>
    <div class="info-item">
        <span class="icon-img">3</span>
        <p>Order complete</p>
    </div>
</div>

<main>
    <section class="cart-container">
        <section class="cart">
            <h2>Selected Products</h2>
            <div class="cart-content">
                <table id="cart-table">
                    <thead>
                        <tr>
                            <th>Product Image</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if ($resultCart->num_rows > 0) {
                            while ($row = $resultCart->fetch_assoc()) {
                                $product_name = $row['product_name'];
                                $quantity = $row['quantity'];
                                $price = $row['price'];
                                $product_id = $row['product_id'];
                                $image_url = $row['image_url']; // Fetch product image URL
                                $subtotal = $quantity * $price;
                                $total_price += $subtotal; // Accumulate total price

                                echo "<tr>
                                        <td><img src='{$image_url}' alt='{$product_name}' style='width: 50px; height: 50px;'></td>
                                        <td>{$product_name}</td>
                                        <td>{$quantity}</td>
                                        <td>\${$price}</td>
                                        <td>\${$subtotal}</td>
                                        <td><button onclick=\"removeFromCart({$product_id})\">Remove</button></td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No items in cart</td></tr>";
                        }

                        $mysqli->close(); // Close database connection
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="total">
                <strong>Total: $<span id="total-price"><?php echo number_format($total_price, 2); ?></span></strong>
            </div>
        </section>
        <section class="cart-summary">
            <h2>Cart Summary</h2>
            <div class="summary-item">
                <span>Subtotal:</span>
                <span id="subtotal-price">$<?php echo number_format($total_price, 2); ?></span>
            </div>
            <div class="summary-item">
                <span>Shipping:</span>
                <span id="shipping-price">$<?php echo number_format($shipping, 2); ?></span>
            </div>
            <div class="summary-item">
                <span>Tax:</span>
                <span id="tax-price">$<?php echo number_format($tax, 2); ?></span>
            </div>
            <div class="summary-item">
                <span>Total:</span>
                <span id="final-total">$<?php echo number_format($final_total, 2); ?></span>
            </div>
            <div class="checkout">
                <button onclick="checkout()">Checkout</button>
                <select id="delivery-address">
                    <option value="">Select Delivery Address</option>
                    <option value="address1">123 Main St</option>
                    <option value="address2">456 Elm St</option>
                </select>
            </div>
        </section>
    </section>
</main>

<script>
    function removeFromCart(productId) {
        const formData = new FormData();
        formData.append('product_id', productId);

        fetch('remove-from-cart.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(data => {
            alert('Product removed from cart');
            location.reload(); // Reload the page to reflect changes
        })
        .catch(error => {
            console.error('Error:', error);
        });
    }

    function checkout() {
        // Implement checkout functionality here
        alert('Proceeding to checkout');
    }
</script>
</body>
</html>
