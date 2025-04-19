<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    session_start();

    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'add_farmer':
                    $stmt = $conn->prepare("INSERT INTO farmers (fullname, email, phone, farm_name, farm_location, password) VALUES (:fullname, :email, :phone, :farm_name, :farm_location, :password)");
                    $stmt->execute([
                        ':fullname' => htmlspecialchars($_POST['fullname']),
                        ':email' => htmlspecialchars($_POST['email']),
                        ':phone' => htmlspecialchars($_POST['phone']),
                        ':farm_name' => htmlspecialchars($_POST['farm_name']),
                        ':farm_location' => htmlspecialchars($_POST['farm_location']),
                        ':password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
                    ]);
                    echo "Farmer added successfully!";
                    break;

                case 'add_product':
                    $targetDir = "images/";
                    $targetFile = $targetDir . basename($_FILES["image"]["name"]);
                    move_uploaded_file($_FILES["image"]["tmp_name"], $targetFile);

                    $stmt = $conn->prepare("INSERT INTO products (name, category, price, image, description) VALUES (:name, :category, :price, :image, :description)");
                    $stmt->execute([
                        ':name' => htmlspecialchars($_POST['name']),
                        ':category' => htmlspecialchars($_POST['category']),
                        ':price' => floatval($_POST['price']),
                        ':image' => basename($_FILES["image"]["name"]),
                        ':description' => htmlspecialchars($_POST['description'] ?? '')
                    ]);
                    echo "Product added successfully!";
                    break;
            }
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>