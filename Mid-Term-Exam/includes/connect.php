

<?php
// This file is used for connecting to the database, there's also a try and catch here to tell you if 
// your database connection has failed 

$host = ""; //hostname
$db = ""; //database name
$user = ""; //username
$password = ""; //password

//points to the database
$dsn = "mysql:host=$host;dbname=$db";

//try to connect
try {
   $pdo = new PDO ($dsn, $user, $password); 
   $pdo->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e) {
    die("Database connection failed: " . $e->getMessage()); 
}
