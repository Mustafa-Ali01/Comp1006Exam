<?php
// admin.php — shows all reviews with Update and Delete options

require "includes/connect.php";

// Get all reviews newest first
$stmt = $pdo->prepare("SELECT * FROM reviews ORDER BY created_at DESC");
$stmt->execute();
$reviews = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Admin</title></head>
<body>

<h1>Admin Page</h1>
<a href="index.php">Back to Form</a>

<?php if (empty($reviews)): ?>
    <p>No reviews yet.</p>
<?php else: ?>
    <table border="1" cellpadding="6">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Author</th>
            <th>Rating</th>
            <th>Review</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($reviews as $review): ?>
        <tr>
            <td><?= htmlspecialchars($review['id']); ?></td>
            <td><?= htmlspecialchars($review['title']); ?></td>
            <td><?= htmlspecialchars($review['author']); ?></td>
            <td><?= htmlspecialchars($review['rating']); ?>/5</td>
            <td><?= htmlspecialchars($review['review_text']); ?></td>
            <td><?= htmlspecialchars($review['created_at']); ?></td>
            <td>
                <a href="update.php?id=<?= urlencode($review['id']); ?>">Update</a>
                <a href="delete.php?id=<?= urlencode($review['id']); ?>" onclick="return confirm('Are you sure you want to delete this review?');">Delete</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

</body>
</html>