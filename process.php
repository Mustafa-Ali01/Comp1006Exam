<?php
// process.php — validates and inserts a new book review
// This file basiclly just processes the inputs the user puts in. Making sure the user is not trying to inject sql statements into the email input location
// the insert uses pre-prepared sql statements so the user cannot inject sql statements where they are meant to input other things.
require "includes/connect.php";

// Only run on POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die('Invalid request');
}

// Sanitize inputs
$title      = trim(filter_input(INPUT_POST, 'title',       FILTER_SANITIZE_SPECIAL_CHARS));
$author     = trim(filter_input(INPUT_POST, 'author',      FILTER_SANITIZE_SPECIAL_CHARS));
$reviewText = trim(filter_input(INPUT_POST, 'review_text', FILTER_SANITIZE_SPECIAL_CHARS));
$ratingRaw  = trim($_POST['rating'] ?? '');

// Validate
$errors = [];

if (empty($title))      $errors[] = "Book Title is required.";
if (empty($author))     $errors[] = "Author is required.";
if (empty($reviewText)) $errors[] = "Review text is required.";

if ($ratingRaw === '') {
    $errors[] = "Rating is required.";
} elseif (filter_var($ratingRaw, FILTER_VALIDATE_INT) === false) {
    $errors[] = "Rating must be a number.";
} else {
    $rating = (int) $ratingRaw;
    if ($rating < 1 || $rating > 5) {
        $errors[] = "Rating must be between 1 and 5.";
    }
}

// Shows the user the errors that aren't allowing them to submit
if (!empty($errors)) {
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
    echo "<a href='index.php'>Go back</a>";
    exit;
}

// Insert using a prepared statement — user input never goes directly into SQL
$sql = "INSERT INTO reviews (title, author, rating, review_text) VALUES (:title, :author, :rating, :review_text)";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':title',       $title);
$stmt->bindParam(':author',      $author);
$stmt->bindParam(':rating',      $rating, PDO::PARAM_INT);
$stmt->bindParam(':review_text', $reviewText);
$stmt->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Success</title></head>
<body>
    <p>Review submitted successfully!</p>
    <a href="index.php">Submit another</a> |
    <a href="admin.php">Go to Admin Page</a>
</body>
</html>
