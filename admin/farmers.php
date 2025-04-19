<?php include 'header.php'; ?>

<div class="card">
    <h2>Manage Farmers</h2><br>
    <button class="btn btn-primary open-modal" data-modal="addFarmerModal">Add Farmer</button><br><br>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Full Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Farm Name</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $conn->query("SELECT * FROM farmers");
        while ($farmer = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$farmer['id']}</td>";
            echo "<td>{$farmer['fullname']}</td>";
            echo "<td>{$farmer['email']}</td>";
            echo "<td>{$farmer['phone']}</td>";
            echo "<td>{$farmer['farm_name']}</td>";
            echo "<td><button class='btn btn-danger'>Delete</button></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<div id="addFarmerModal" class="modal">
    <div class="modal-content">
        <span class="close">×</span>
        <h2>Add New Farmer</h2>
        <form action="process.php" method="POST">
            <input type="hidden" name="action" value="add_farmer">
            <input type="text" name="fullname" placeholder="Full Name" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="text" name="phone" placeholder="Phone" required>
            <input type="text" name="farm_name" placeholder="Farm Name" required>
            <input type="text" name="farm_location" placeholder="Farm Location" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit" class="btn btn-primary">Add Farmer</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>