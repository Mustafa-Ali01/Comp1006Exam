<?php
require 'includes/connect.php';

// Access control: Ensure only logged-in admins can manage the gallery
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit;
}

$msg = "";

// Handle the image upload process
if (isset($_POST['upload'])) {
    $title = trim($_POST['title']);
    $file = $_FILES['image'];
    $allowed = ['image/jpeg', 'image/png', 'image/jpg'];

    // Validation for title and file type
    if (empty($title) || !in_array($file['type'], $allowed)) {
        $msg = "Error: Title is required and file must be a valid image.";
    } else {
        // Generate path with timestamp to prevent filename conflicts
        $path = "uploads/" . time() . "_" . basename($file['name']);
        
        if (move_uploaded_file($file['tmp_name'], $path)) {
            // Prepared statement to store title and file path
            $stmt = $pdo->prepare("INSERT INTO images (title, file_path) VALUES (?, ?)");
            $stmt->execute([$title, $path]);
            $msg = "Image uploaded successfully!";
        }
    }
}

