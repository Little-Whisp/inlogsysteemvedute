<?php
session_start();

// Prevent direct access without login
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: index.php");
    exit;
}

// Get email from the session
$email = $_SESSION['loggedInUser']['email'];
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Page</title>
</head>
<body>
<h2>Secure Page</h2>

<ul>
    <li><p>You are logged in! Welcome, <?= $email ?></p></li>
</ul>

<ul>
    <li><a href="logoutpage.php">Logout</a></li>
</ul>

<p>
<ul>
    <li><a href="homepage.php">Homepage</a></li>
</ul>
</p>
</body>
</html>
