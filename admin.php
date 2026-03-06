<?php
// admin.php — shows all reviews with Update and Delete options

require "includes/connect.php";

// Handle delete
if (isset($_GET['action']) && $_GET['action'] === 'delete' && isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM reviews WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    header("Location: admin.php");
    exit;
}

// Get all reviews newest first
$stmt = $pdo->prepare("SELECT * FROM reviews ORDER BY created_at DESC");
$stmt->execute();
$reviews = $stmt->fetchAll();
?>

