<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products</title>
    <link rel="stylesheet" href="products.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f7f7f7;
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

        .main-content input[type="text"] {
            padding: 10px;
            border: 1px solid #e1e1e1;
            border-radius: 5px;
        }

        .user-info {
            color: #121416; /* Color 3 */
        }

        .table-container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            padding: 12px 15px;
            border: 1px solid #e1e1e1;
            text-align: left;
        }

        table th {
            background-color: #eb7324; /* Color 2 */
            color: #ffffff;
            font-weight: bold;
        }

        table tr:nth-child(even) {
            background-color: #f2f2f2;
        }

        /* Adjust table size */
        table {
            
        }

        table th, table td {
            text-overflow: ellipsis;
            overflow: hidden;
            white-space: nowrap;
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
            
            <h2>Products</h2>

            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Product Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Stock</th>
                            <th>Size</th> <!-- Added column for Size -->
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Products will be listed here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</body>
</html>
