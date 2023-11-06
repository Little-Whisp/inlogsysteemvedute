<?php

/** @var mysqli $db */

session_start();

//I want to check if the user is logged in or not
if(isset($_SESSION['loggedInUser'])) {
    $login = true;
} else {
    $login = false;
}


/*These are for the SQL Injection*/
if (isset($_POST['submit'])) {

    //I use require_once to only make connection with the database when I use the submit button.
    require_once "includes/database.php";

    //These are for the SQL Injections//
    $email = mysqli_escape_string($db, $_POST['email']);
    $password = $_POST['password'];

    //if you didn't fill in your email or password you'll see errors.
    $errors = [];
    if($email == '') {
        $errors['email'] = 'Fill in your email';
    }
    if($password == '') {
        $errors['password'] = 'Fill in your password';
    }


    if(empty($errors))
    {
        //I want to get information based on the email
        $query = "SELECT * FROM users WHERE email='$email'";
        $result = mysqli_query($db, $query);

        //if the number from the row number result is equal to 1.
        if (mysqli_num_rows($result) == 1) {
            //the user will be the result that was fetched.
            $user = mysqli_fetch_assoc($result);

            //I use a password verify to check if the password is linked to the user.
            //When your password has been verified it should log you in.
            if (password_verify($password, $user['password'])) {
                $login = true;

                $_SESSION['loggedInUser'] = [
                    'email' => $user['email'],
                    'id' => $user['id']
                ];


            } else {
                //Error if your login information is incorrect
                $errors['loginFailed'] = 'The combination of the email and password are not known';
            }
        } else {
            //Error if your login information is incorrect
            $errors['loginFailed'] = 'The combination of the email and password are not known';
        }
        //If you're logged in you will be directed to the secure page
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
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="Stylesheet/Stylesheet.css?v=<?php echo time(); ?>">

    <title>Login</title>
</head>
<body>

<h2>log in</h2>

<section>

<?php if ($login) { ?>
    <p>You are loggen in!</p>
    <p><a href="logoutpage.php">Log out</a> / <a href="secure.php">To secure page</a></p>

<?php } else { ?>
    <form action="" method="post">
        <div>
            <label for="email">Email</label>
            <input id="email" type="text" name="email" value="<?= $email ?? '' ?>"/>
            <!-- If 'email' field is not filled in it will show error = 'email can't be empty' -->
            <span class="errors"><?= $errors['email'] ?? '' ?></span>
        </div>
        <div>
            <label for="password">Password</label>
            <input id="password" type="password" name="password" />
            <!-- If 'password' field is not filled in it will show error = 'password can't be empty' -->
            <span class="errors"><?= $errors['password'] ?? '' ?></span>
        </div>
        <div>
            <!-- If logging in did not work because you did not fill in the correct information, you will get
             an error that login failed-->
            <p class="errors"><?= $errors['loginFailed'] ?? '' ?></p>
            <input type="submit" name="submit" value="Login"/>
        </div>
    </form>

    <ul>
        <h1>
            <a href="register.php">Don't have an account yet?</a>

            <h1>
    </ul>
</section>
<?php } ?>

</body>
</html>