<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

if (isset($_POST['add_product'])) {
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $category_id = filter_var($_POST['category_id'], FILTER_SANITIZE_NUMBER_INT);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $stock = filter_var($_POST['stock'], FILTER_SANITIZE_NUMBER_INT);
    $image = $_FILES['image']['name'];

    // Validate inputs
    if (empty($name) || $price <= 0 || $category_id <= 0 || $stock < 0 || empty($image)) {
        $error = "All fields are required and must be valid!";
    } else {
        // Handle image upload
        $target_dir = "../images/";
        $target_file = $target_dir . basename($image);
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0755, true);
        }
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            try {
                $stmt = $conn->prepare("INSERT INTO products (name, price, image, category_id, description, stock) 
                                      VALUES (:name, :price, :image, :category_id, :description, :stock)");
                $stmt->bindParam(':name', $name);
                $stmt->bindParam(':price', $price, PDO::PARAM_STR);
                $stmt->bindParam(':image', $image);
                $stmt->bindParam(':category_id', $category_id, PDO::PARAM_INT);
                $stmt->bindParam(':description', $description);
                $stmt->bindParam(':stock', $stock, PDO::PARAM_INT);

                if ($stmt->execute()) {
                    $success = "Product added successfully!";
                } else {
                    $error = "Failed to add product!";
                }
            } catch (PDOException $e) {
                error_log("Add product error: " . $e->getMessage());
                $error = "Database error occurred!";
            }
        } else {
            $error = "Failed to upload image!";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f9f9f9;
        }
        h2 {
            color: #28a745;
            text-align: center;
        }
        .error {
            color: red;
            text-align: center;
        }
        .success {
            color: green;
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }
        label {
            font-weight: bold;
        }
        input, textarea {
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        button {
            background-color: #28a745;
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        button:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
    <h2>Add Product</h2>
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>
    <?php if (isset($success)): ?>
        <p class="success"><?php echo htmlspecialchars($success); ?></p>
    <?php endif; ?>
    <form method="POST" enctype="multipart/form-data">
        <label>Name:</label>
        <input type="text" name="name" required>
        <label>Price:</label>
        <input type="number" name="price" step="0.01" min="0" required>
        <label>Category ID:</label>
        <input type="number" name="category_id" min="1" required>
        <label>Description:</label>
        <textarea name="description"></textarea>
        <label>Stock:</label>
        <input type="number" name="stock" min="0" required>
        <label>Image:</label>
        <input type="file" name="image" accept="image/*" required>
        <button type="submit" name="add_product">Add Product</button>
    </form>
    <p><a href="logout.php">Logout</a></p>
</body>
</html>