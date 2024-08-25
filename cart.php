<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
    <?php include 'navbar.php'; ?>
    </header>

    <style>
        /* Basic Reset */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        /* Body Styling */
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
        }

        /* Header */
        header {
            background-color: #333;
            color: #fff;
            padding: 10px 0;
        }

        nav {
            display: flex;
            justify-content: center;
        }

        /* Main Content */
        main {
            padding: 20px;
            display: flex;
            justify-content: center;
        }

        .cart-container {
            max-width: 1200px;
            width: 100%;
        }

        .cart {
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
            padding: 20px;
        }

        .cart-items {
            flex: 3;
            margin-right: 20px;
        }

        .cart-header {
            display: grid;
            grid-template-columns: 1fr 3fr 1fr 1fr 1fr 1fr;
            background-color: #efefef;
            padding: 10px;
            border-bottom: 2px solid #ddd;
            font-weight: bold;
        }

        .header-item {
            text-align: center;
        }

        .cart-item {
            display: grid;
            grid-template-columns: 1fr 3fr 1fr 1fr 1fr 1fr;
            padding: 10px;
            border-bottom: 1px solid #ddd;
        }

        .item-image {
            width: 100px;
            height: 100px;
            background-size: cover;
            background-position: center;
        }

        .item-product, .item-price, .item-quantity, .item-total, .item-remove {
            text-align: center;
        }

        .item-quantity input {
            width: 60px;
            text-align: center;
        }

        .remove-btn {
            background-color: #e74c3c;
            color: #fff;
            border: none;
            padding: 5px 10px;
            border-radius: 3px;
            cursor: pointer;
        }

        .remove-btn:hover {
            background-color: #c0392b;
        }

        .cart-totals {
            flex: 1;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .totals-header {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .totals-row {
            display: flex;
            justify-content: space-between;
            padding: 5px 0;
        }

        .label {
            font-weight: bold;
        }

        .value {
            color: #333;
        }

        .proceed-checkout {
            background-color: #27ae60;
            margin-top: 20px;
        }

        .proceed-checkout:hover {
            background-color: #2ecc71;
        }

        /* Buttons */
        .cart-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }

        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            color: #fff;
            cursor: pointer;
            flex: 1;
        }

        .update-cart {
            background-color: #3498db;
            margin-right: 10px;
        }

        .update-cart:hover {
            background-color: #2980b9;
        }

        .continue-shopping {
            background-color: #27ae60;
            margin-left: 10px;
        }

        .continue-shopping:hover {
            background-color: #2ecc71;
        }

        /* Info Section */
        .info-item {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }

        .info-item .icon-img {
            font-size: 24px;
            margin-right: 10px;
        }

        .info-item p {
            margin: 0;
        }
    </style>

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

    <main>
        <div class="cart-container">
            <div class="cart">
                <div class="cart-items">
                    <div class="cart-header">
                        <div class="header-item">Image</div>
                        <div class="header-item">Product</div>
                        <div class="header-item">Price</div>
                        <div class="header-item">Quantity</div>
                        <div class="header-item">Total</div>
                        <div class="header-item">Remove</div>
                    </div>
                    
                    <div class="cart-item">
                        <div class="item-image" style="background-image: url('images/product1.jpg');"></div>
                        <div class="item-product">Product 1</div>
                        <div class="item-price">$49.00</div>
                        <div class="item-quantity"><input type="number" value="1" min="1" max="100"></div>
                        <div class="item-total">$49.00</div>
                        <div class="item-remove"><button class="remove-btn">Remove</button></div>
                    </div>
                    
                    <div class="cart-item">
                        <div class="item-image" style="background-image: url('images/product2.jpg');"></div>
                        <div class="item-product">Product 2</div>
                        <div class="item-price">$49.00</div>
                        <div class="item-quantity"><input type="number" value="1" min="1" max="100"></div>
                        <div class="item-total">$49.00</div>
                        <div class="item-remove"><button class="remove-btn">Remove</button></div>
                    </div>
                </div>

                <div class="cart-totals">
                    <div class="totals-header">Cart Totals</div>
                    <div class="totals-row">
                        <span class="label">Subtotal</span>
                        <span class="value">$230.00</span>
                    </div>
                    <div class="totals-row">
                        <span class="label">Total</span>
                        <span class="value">$230.00</span>
                    </div>
                    <button class="btn proceed-checkout">Proceed to Checkout</button>
                </div>
            </div>
        </div>
    </main>

    <!-- Buttons for updating cart and continuing shopping -->
    <div class="cart-buttons">
        <button class="btn update-cart">Update Cart</button>
        <button class="btn continue-shopping">Continue Shopping</button>
    </div>
</body>
</html>
