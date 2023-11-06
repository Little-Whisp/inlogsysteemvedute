<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['loggedInUser'])) {
    header("Location: index.php"); // Redirect to the login page if not logged in
    exit;
}

$email = $_SESSION['loggedInUser']['email'];

// HTML for the homepage
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
</head>
<body>
<h1>Welcome, <?= $email ?></h1>
<p>This is your homepage.</p>
<a href="logoutpage.php">Logout</a>
</body>
</html>
