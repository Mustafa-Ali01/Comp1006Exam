<?php
require 'includes/connect.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$msg = "";

if (isset($_POST['upload'])) {
    $title = trim($_POST['title']);
    $file = $_FILES['image'];
    $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

    if (empty($title) || !in_array($file['type'], $allowed)) {
        $msg = "Error: Title is required and file must be an image.";
    } else {
        $path = "uploads/" . time() . "_" . basename($file['name']);
        if (move_uploaded_file($file['tmp_name'], $path)) {
            $stmt = $pdo->prepare("INSERT INTO images (title, file_path) VALUES (?, ?)");
            $stmt->execute([$title, $path]);
            $msg = "Image uploaded successfully!";
        }
    }
}

if (isset($_GET['del'])) {
    $id = $_GET['del'];
    $stmt = $pdo->prepare("SELECT file_path FROM images WHERE id = ?");
    $stmt->execute([$id]);
    $img = $stmt->fetch();
    
    if ($img) {
        if (file_exists($img['file_path'])) unlink($img['file_path']);
        $pdo->prepare("DELETE FROM images WHERE id = ?")->execute([$id]);
        $msg = "Image deleted.";
    }
}

$images = $pdo->query("SELECT * FROM images ORDER BY id DESC")->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Gallery</title>
</head>
<body>

    <h1>Gallery Manager</h1>
    <div class="admin-info">
        Logged in as: <?= htmlspecialchars($_SESSION['admin']) ?> | <a href="logout.php">Logout</a>
    </div>
    
    <?php if ($msg): ?>
        <p class="msg"><?= $msg ?></p>
    <?php endif; ?>

    <form method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="Image Title" required>
        <input type="file" name="image" required>
        <button name="upload">Upload</button>
    </form>

    <hr>

    <div class="gallery-grid">
        <?php foreach ($images as $row): ?>
            <div class="gallery-item">
                <p><?= htmlspecialchars($row['title']) ?></p>
                <img src="<?= $row['file_path'] ?>" width="150">
                <a href="?del=<?= $row['id'] ?>" class="btn-delete" onclick="return confirm('Delete this image?')">Delete</a>
            </div>
        <?php endforeach; ?>
    </div>

</body>
</html>