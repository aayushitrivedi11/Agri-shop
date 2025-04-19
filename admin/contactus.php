<?php include 'header.php'; ?>

<div class="card">
    <h2>Manage Contact Messages</h2>
    <table class="table">
        <tr>
            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>Message</th>
            <th>Created At</th>
            <th>Actions</th>
        </tr>
        <?php
        $stmt = $conn->query("SELECT * FROM contactus");
        while ($contact = $stmt->fetch(PDO::FETCH_ASSOC)) {
            echo "<tr>";
            echo "<td>{$contact['id']}</td>";
            echo "<td>{$contact['name']}</td>";
            echo "<td>{$contact['email']}</td>";
            echo "<td>{$contact['message']}</td>";
            echo "<td>{$contact['created_at']}</td>";
            echo "<td><button class='btn btn-danger'>Delete</button></td>";
            echo "</tr>";
        }
        ?>
    </table>
</div>

<?php include 'footer.php'; ?>