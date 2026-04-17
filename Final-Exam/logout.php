<?php
session_start();
session_destroy(); // Clears admin session
header("Location: index.php");
exit;