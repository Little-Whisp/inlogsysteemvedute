<?php
// General settings
$host = "localhost";
$database = "vedute";
$user = "root";
$password = "";

$db = mysqli_connect($host, $user, $password, $database)
or die("Error: " . mysqli_connect_error());

session_start();

// Check if the user is logged in or not
if(isset($_SESSION['loggedInUser'])) {
    $loggedIn = true;
} else {
    $loggedIn = false;
}

if (isset($_POST['submit'])) {
    // Include the database connection script
    require_once "includes/database.php";

    // Sanitize user input to prevent SQL injection
    $email = mysqli_real_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    $errors = [];
    if (empty($email)) {
        $errors['email'] = 'Fill in your email';
    }
    if (empty($password)) {
        $errors['password'] = 'Fill in your password';
    }

    if (empty($errors)) {
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($db, $query);

        if (mysqli_num_rows($result) == 1) {
            $user = mysqli_fetch_assoc($result);

            if (password_verify($password, $user['password'])) {
                $loggedIn = true;

                $_SESSION['loggedInUser'] = [
                    'email' => $user['email'],
                    'id' => $user['id']
                ];

            } else {
                $errors['loginFailed'] = 'The combination of email and password is not recognized';
            }
        } else {
            $errors['loginFailed'] = 'The combination of email and password is not recognized';
        }

        if ($result) {
            header('Location: secure.php');
            exit;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>

<h2>Login</h2>

<section>

    <?php if ($loggedIn) { ?>
        <p>You are logged in!</p>
        <p><a href="logoutpage.php">Log out</a> / <a href="secure.php">Go to secure page</a></p>

    <?php } else { ?>
        <form action="" method="post">
            <div>
                <label for="email">Email</label>
                <input id="email" type="text" name="email" value="<?= $email ?? '' ?>"/>
                <span class="errors"><?= $errors['email'] ?? '' ?></span>
            </div>
            <div>
                <label for="password">Password</label>
                <input id="password" type="password" name="password" />
                <span class="errors"><?= $errors['password'] ?? '' ?></span>
            </div>
            <div>
                <p class="errors"><?= $errors['loginFailed'] ?? '' ?></p>
                <input type="submit" name="submit" value="Login"/>
            </div>
        </form>

        <ul>
            <li><a href="register.php">Don't have an account yet?</a></li>
        </ul>
    <?php } ?>
</section>
</body>
</html>
