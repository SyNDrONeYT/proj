<?php
session_start();
include('connection.php'); // Include the MySQLi database connection

// Ensure cart is initialized
if (!isset($_SESSION['cart']) || count($_SESSION['cart']) === 0) {
    header("Location: #");
    exit; // Redirect to products page if cart is empty
}

// Initialize variables
$total = 0;

// Calculate total
foreach ($_SESSION['cart'] as $cart_item) {
    $total += $cart_item['price'];
}

// Handle form submission for checkout
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture user shipping details
    $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_STRING);
    $address = filter_input(INPUT_POST, 'address', FILTER_SANITIZE_STRING);
    $phone = filter_input(INPUT_POST, 'phone', FILTER_SANITIZE_STRING);
    $payment_method = filter_input(INPUT_POST, 'payment_method', FILTER_SANITIZE_STRING);

    // Basic validation
    if ($name && $address && $phone) {
        // Store order in the database (you'll need to implement this part)
        // Example: Insert into orders table and clear cart
        // You can also implement a function to handle payment processing.

        // Clear the cart
        unset($_SESSION['cart']);
        // Redirect to a confirmation page (not implemented in this example)
        header("Location: order_confirmation.php");
        exit;
    } else {
        $error = "Please fill in all fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link rel="stylesheet" href="istyle.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }

        .navbar {
            background-color: #007bff;
            padding: 15px;
            color: white;
        }

        .navbar h2 {
            margin: 0;
            display: inline-block;
        }

        .navbar .nav {
            list-style: none;
            float: right;
            margin: 0;
            padding: 0;
        }

        .navbar .nav li {
            display: inline;
            margin-left: 20px;
        }

        .navbar .nav a {
            color: white;
            text-decoration: none;
            font-weight: bold;
        }

        .checkout-section {
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            color: #333;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        .btn {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            display: block;
            width: 100%;
            text-align: center;
        }

        .btn:hover {
            background-color: #0056b3;
        }

        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }

        .order-summary {
            margin-top: 20px;
        }

        .order-summary h2 {
            margin: 0 0 10px;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-summary th, .order-summary td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

        .order-summary th {
            background-color: #f2f2f2;
        }

    </style>
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <h2>Sports Co-op</h2>
            <ul class="nav">
                <li><a href="products.php">Products</a></li>
                <li><a href="membership.php">Membership</a></li>
                <li><a href="support.php">Support Us</a></li>
                <li><a href="cart2.php">Cart(<?php echo count($_SESSION['cart']); ?>)</a></li>
                <li><a href="profile.php">Profile</a></li>
            </ul>
        </div>
    </nav>

    <section class="checkout-section">
        <h1>Checkout</h1>

        <?php if (isset($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="payment_details.php?name="<?php echo $cart_item['name']; ?>"&price="<?php echo $cart_item['price']">
            <div class="form-group">
                <label for="name">Name:</label>
                <input type="text" name="name" id="name" required>
            </div>

            <div class="form-group">
                <label for="address">Address:</label>
                <input type="text" name="address" id="address" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" required>
            </div>

            <div class="form-group">
                <label for="payment_method">Payment Method:</label>
                <select name="payment_method" id="payment_method" required>
                    <option value="card">Card Payment</option>
                    <option value="cod">Cash on Delivery</option>
                </select>
            </div>

            <button type="submit" class="btn">Place Order</button>
        </form>

        <div class="order-summary">
            <h2>Order Summary</h2>
            <table>
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($_SESSION['cart'] as $cart_item): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($cart_item['name']); ?></td>
                        <td>₹<?php echo htmlspecialchars($cart_item['price']); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Total: ₹<?php echo $total; ?></h3>
        </div>
    </section>
</body>
</html>
