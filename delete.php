<?php
// delete.php — deletes a review, then redirects back to admin

require 'includes/connect.php';

// get the id from the URL
$id = $_GET['id'];

// create the query
$sql = "DELETE FROM reviews WHERE id = :id";

// prepare
$stmt = $pdo->prepare($sql);

// bind
$stmt->bindParam(':id', $id);

// execute
$stmt->execute();

// redirect back to admin
header("Location: admin.php");
exit;