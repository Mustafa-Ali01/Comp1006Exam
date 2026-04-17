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