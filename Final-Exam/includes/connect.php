<?php
$host = ' ';
$db   = '';
$user = '';
$pass = '';

try {
    //Use PDO for all database operations
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    // Set error mode to exception to catch issues early
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

//Use sessions to restrict access
session_start();
?>