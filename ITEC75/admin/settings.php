<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="stylesheet" href="settings.css">
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
                    <span>User</span> <span>Malorum</span> <span>Administrator</span>
                </div>
            </header>
            
            <h2>Settings</h2>
            <form>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" required>
                
                <button type="submit">Update Settings</button>
            </form>
        </div>
    </div>
</body>
</html>
