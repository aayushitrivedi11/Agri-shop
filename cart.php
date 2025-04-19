<?php
require_once 'config.php'; // Ensure $conn and session_start() are defined
ob_start(); // Start output buffering to handle redirects

// Debug: Log session data
error_log("Session data: " . print_r($_SESSION, true));

// Handle Remove from Cart
if (isset($_POST['remove_from_cart'])) {
    $cart_id = filter_var($_POST['cart_id'] ?? 0, FILTER_SANITIZE_NUMBER_INT);
    error_log("Remove attempt: cart_id=$cart_id, farmer_id=" . ($_SESSION['farmer_id'] ?? 'not set'));

    if ($cart_id <= 0) {
        $_SESSION['cart_message'] = "Invalid cart ID!";
        header("Location: cart.php");
        ob_end_flush();
        exit;
    }

    try {
        $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = :cart_id AND farmer_id = :farmer_id");
        $stmt->bindParam(':cart_id', $cart_id, PDO::PARAM_INT);
        $stmt->bindParam(':farmer_id', $_SESSION['farmer_id'], PDO::PARAM_INT);
        $stmt->execute();
        $rowsAffected = $stmt->rowCount();
        error_log("Delete executed: rows affected=$rowsAffected");

        if ($rowsAffected > 0) {
            $_SESSION['cart_message'] = "Product removed from cart successfully!";
        } else {
            $_SESSION['cart_message'] = "Item not found in your cart!";
        }
        header("Location: cart.php");
        ob_end_flush();
        exit;
    } catch (PDOException $e) {
        error_log("Remove error: " . $e->getMessage());
        $_SESSION['cart_message'] = "Database error occurred. Please try again.";
        header("Location: cart.php");
        ob_end_flush();
        exit;
    }
}

// Fetch and Merge Cart Items
$cart_items = [];
$total_price = 0;
if (isset($_SESSION['farmer_id'])) {
    try {
        // Test query to verify database connection
        $test_stmt = $conn->query("SELECT 1");
        error_log("Database connection test: " . ($test_stmt->fetchColumn() === '1' ? 'Success' : 'Failed'));

        // Fetch raw cart data
        $stmt = $conn->prepare("SELECT c.cart_id, c.product_id, c.quantity, p.name, p.price, p.image 
                               FROM cart c 
                                JOIN products p ON c.product_id = p.id 
                               WHERE c.farmer_id = :farmer_id");
        $stmt->bindParam(':farmer_id', $_SESSION['farmer_id'], PDO::PARAM_INT);
        $stmt->execute();
        $raw_cart_items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Debug: Log fetched items
        error_log("Fetched raw cart items: " . print_r($raw_cart_items, true));

        // Group by product_id to merge duplicates
        $merged_items = [];
        foreach ($raw_cart_items as $item) {
            $product_id = $item['product_id'] ?? null;
            if ($product_id !== null) {
                if (!isset($merged_items[$product_id])) {
                    $merged_items[$product_id] = $item;
                } else {
                    $merged_items[$product_id]['quantity'] += $item['quantity'];
                }
            } else {
                error_log("Invalid item detected (no product_id): " . print_r($item, true));
                if (isset($item['cart_id'])) {
                    $stmt = $conn->prepare("DELETE FROM cart WHERE cart_id = :cart_id");
                    $stmt->bindParam(':cart_id', $item['cart_id'], PDO::PARAM_INT);
                    $stmt->execute();
                    error_log("Removed orphaned cart entry with cart_id: " . $item['cart_id']);
                }
            }
        }

        // Convert merged array to list and filter valid items
        $cart_items = array_values(array_filter($merged_items, function ($item) {
            return isset($item['product_id']) && $item['product_id'] !== null;
        }));

        // Recalculate total price only from valid items
        foreach ($cart_items as $item) {
            if (isset($item['price']) && isset($item['quantity'])) {
                $total_price += $item['price'] * $item['quantity'];
            } else {
                error_log("Missing price or quantity for product_id " . ($item['product_id'] ?? 'unknown') . ": " . print_r($item, true));
            }
        }
    } catch (PDOException $e) {
        error_log("Fetch cart error: " . $e->getMessage());
        $_SESSION['cart_message'] = "Failed to load cart items.";
    }
} else {
    $_SESSION['cart_message'] = "Please log in to view your cart.";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Agroshop - Cart</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
            color: #333;
        }
        .cart {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .cart h2 {
            text-align: center;
            color: #28a745;
            margin-bottom: 20px;
        }
        .success-message {
            text-align: center;
            color: #28a745;
            margin-bottom: 20px;
        }
        .cart-items {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: center;
        }
        .cart-item {
            display: flex;
            align-items: center;
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            width: 100%;
            max-width: 500px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        .cart-item:hover {
            transform: translateY(-5px);
        }
        .cart-item img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            margin-right: 15px;
            border-radius: 5px;
        }
        .cart-item div {
            flex-grow: 1;
        }
        .cart-item h3 {
            margin: 0 0 10px;
            color: #333;
        }
        .cart-item p {
            margin: 5px 0;
            color: #666;
        }
        .cart-item form {
            margin-top: 10px;
        }
        .cart-item button {
            background: #dc3545;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            transition: background 0.3s;
        }
        .cart-item button:hover {
            background: #c82333;
        }
        .empty-state {
            text-align: center;
            padding: 40px;
        }
        .empty-state .empty-image {
            width: 200px;
            height: auto;
            margin-bottom: 20px;
        }
        .total {
            text-align: right;
            margin-top: 20px;
            font-size: 18px;
            color: #28a745;
            font-weight: bold;
        }
        @media (max-width: 768px) {
            .cart-item {
                flex-direction: column;
                text-align: center;
            }
            .cart-item img {
                margin-right: 0;
                margin-bottom: 10px;
            }
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>

    <main>
        <section class="cart">
            <h2>Your Cart</h2>
            <?php if (isset($_SESSION['cart_message'])): ?>
                <p class="success-message"><?= htmlspecialchars($_SESSION['cart_message']) ?></p>
                <?php unset($_SESSION['cart_message']); ?>
            <?php endif; ?>
            <?php if ($cart_items && is_array($cart_items) && count($cart_items) > 0): ?>
                <div class="cart-items">
                    <?php foreach ($cart_items as $item): ?>
                        <div class="cart-item">
                            <img src="images/<?= isset($item['image']) ? htmlspecialchars($item['image']) : 'default-image.jpg' ?>" alt="<?= isset($item['name']) ? htmlspecialchars($item['name']) : 'Product' ?>">
                            <div>
                                <h3><?= isset($item['name']) ? htmlspecialchars($item['name']) : 'Unnamed Product (product_id: ' . (isset($item['product_id']) ? $item['product_id'] : 'unknown') . ')' ?></h3>
                                <p>Price: $<?= isset($item['price']) ? number_format($item['price'], 2) : '0.00' ?></p>
                                <p>Quantity: <?= isset($item['quantity']) ? htmlspecialchars($item['quantity']) : '1' ?></p>
                                <form method="POST">
                                    <input type="hidden" name="cart_id" value="<?= isset($item['cart_id']) ? htmlspecialchars($item['cart_id']) : '0' ?>">
                                    <button type="submit" name="remove_from_cart">Remove</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="total">
                    Total: $<?= number_format($total_price, 2) ?>
                </div>
            <?php else: ?>
                <div class="empty-state">
                    <img src="images/cart-empty.png" alt="Empty Cart" class="empty-image">
                    <p>Your cart is empty.</p>
                </div>
            <?php endif; ?>
        </section>
    </main>

    <?php include 'footer.php'; ?>

</body>
</html>

<?php ob_end_flush(); // End output buffering ?>