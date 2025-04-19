<?php include 'header.php'; 
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}?>
<div class="card">
    <h2>Dashboard Overview</h2>
    <?php
    $totalFarmers = $conn->query("SELECT COUNT(*) FROM farmers")->fetchColumn();
    $totalProducts = $conn->query("SELECT COUNT(*) FROM products")->fetchColumn();
    $totalCartItems = $conn->query("SELECT COUNT(*) FROM cart")->fetchColumn();
    $totalWishlistItems = $conn->query("SELECT COUNT(*) FROM wishlist")->fetchColumn();
    $totalMessages = $conn->query("SELECT COUNT(*) FROM contactus")->fetchColumn();
    ?>
    <div style="display: flex; gap: 20px; justify-content: space-around;">
        <div style="background: #28a745; color: white; padding: 15px; border-radius: 8px; flex: 1;">
            <h3>Farmers</h3>
            <p><?= $totalFarmers ?></p>
        </div>
        <div style="background: #28a745; color: white; padding: 15px; border-radius: 8px; flex: 1;">
            <h3>Products</h3>
            <p><?= $totalProducts ?></p>
        </div>
        <div style="background: #28a745; color: white; padding: 15px; border-radius: 8px; flex: 1;">
            <h3>Messages</h3>
            <p><?= $totalMessages ?></p>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>