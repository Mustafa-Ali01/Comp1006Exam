<?php
// update.php — load a review into a form and save changes

require "includes/connect.php";

if (!isset($_GET['id'])) {
    die("No review ID provided.");
}

$id = $_GET['id'];

// If form submitted, update the row
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title      = trim($_POST['title']       ?? '');
    $author     = trim($_POST['author']      ?? '');
    $reviewText = trim($_POST['review_text'] ?? '');
    $rating     = (int)($_POST['rating']     ?? 0);

    // Validate
    $errors = [];
    if (empty($title))      $errors[] = "Book Title is required.";
    if (empty($author))     $errors[] = "Author is required.";
    if (empty($reviewText)) $errors[] = "Review text is required.";
    if ($rating < 1 || $rating > 5) $errors[] = "Rating must be between 1 and 5.";

    if (empty($errors)) {
        $sql = "UPDATE reviews SET title = :title, author = :author, rating = :rating, review_text = :review_text WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':title',       $title);
        $stmt->bindParam(':author',      $author);
        $stmt->bindParam(':rating',      $rating, PDO::PARAM_INT);
        $stmt->bindParam(':review_text', $reviewText);
        $stmt->bindParam(':id',          $id);
        $stmt->execute();

        // Redirect back to admin after saving
        header("Location: admin.php");
        exit;
    }

    // Show errors if any
    echo "<ul>";
    foreach ($errors as $error) {
        echo "<li>" . htmlspecialchars($error) . "</li>";
    }
    echo "</ul>";
}

// Load the existing review to pre-fill the form
$stmt = $pdo->prepare("SELECT * FROM reviews WHERE id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$review = $stmt->fetch();

if (!$review) {
    die("Review not found.");
}
?>

<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Update Review</title></head>
<body>

<h1>Update Review</h1>

<form method="post">

    <label for="title">Book Title:</label>
    <input type="text" id="title" name="title" value="<?= htmlspecialchars($review['title']); ?>">

    <label for="author">Author:</label>
    <input type="text" id="author" name="author" value="<?= htmlspecialchars($review['author']); ?>">

    <label for="rating">Rating (1 to 5):</label>
    <input type="number" id="rating" name="rating" min="1" max="5" value="<?= (int)$review['rating']; ?>">

    <label for="review_text">Review:</label>
    <textarea id="review_text" name="review_text" rows="6" cols="40"><?= htmlspecialchars($review['review_text']); ?></textarea>

    <button type="submit">Save Changes</button>

</form>

<a href="admin.php">Cancel</a>

</body>
</html>
