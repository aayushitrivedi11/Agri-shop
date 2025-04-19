<?php require_once 'config.php';

// Fetch Categories
$categories = [
    'herbicides',
    'growth-promoters',
    'fungicides',
    'vegetables',
    'fruit-seeds',
    'farm-machinery',
    'nutrients',
    'flower-seeds',
    'insecticides',
    'organic-farming',
    'animal-husbandry'
];

// Fetch Products for Selected Category
$selected_category = isset($_GET['category']) ? filter_var($_GET['category'], FILTER_SANITIZE_STRING) : 'herbicides';
$stmt = $conn->prepare("SELECT * FROM products WHERE category = :category");
$stmt->bindParam(':category', $selected_category);
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>AgriShop - Products</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <?php include 'header.php'; ?>


    <main>
        <div class="products-container">
            <aside class="sidebar">
                <h3>Categories</h3>
                <ul class="category-list">
                    <?php foreach ($categories as $category): ?>
                        <li><a href="?category=<?= htmlspecialchars($category) ?>"
                                class="<?= $selected_category === $category ? 'active' : '' ?>">
                                <?= ucwords(str_replace('-', ' ', $category)) ?>
                            </a></li>
                    <?php endforeach; ?>
                </ul>
            </aside>

            <section class="product-grid">
                <?php if ($products): ?>
                    <?php foreach ($products as $product): ?>
                        <div class="products-grid">
                            <?php foreach ($products as $product): ?>
                                <div class="product-card">
                                    <div class="product-image">
                                        <img src="images/<?= htmlspecialchars($product['image']) ?>"
                                            alt="<?= htmlspecialchars($product['name']) ?>">
                                    </div>
                                    <div class="product-info">
                                        <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                                        <p class="product-price">$<?= number_format($product['price'], 2) ?></p>
                                    </div>
                                    <div class="product-actions">
                                        <form action="process.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <button type="submit" name="add_to_cart" class="btn btn-cart">Add to Cart</button>
                                        </form>
                                        <form action="process.php" method="POST">
                                            <input type="hidden" name="product_id" value="<?= $product['id'] ?>">
                                            <button type="submit" name="add_to_wishlist" class="btn btn-wishlist">Add to
                                                Wishlist</button>
                                        </form>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p>No products found in this category.</p>
                <?php endif; ?>
            </section>
            
        </div>
    </main>
    <?php include 'footer.php'; ?>

    <script src="script.js"></script>
</body>

</html>