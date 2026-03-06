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

