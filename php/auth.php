<?php

require_once("./_connect.php");

if (!isset($_POST['txtUser']) || !isset($_POST['txtPass']) || !isset($_POST['token']))
{
    die("you're dead");
}

$username = $_POST['txtUser'];
$password = $_POST['txtPass'];

$captcha = $_POST['token'];
$secretKey = '6LdwAmEpAAAAAOzWh9OPf1L_a6wOyx7QxmInbj4E';
$reCAPTCHA = json_decode(file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . urlencode($secretKey) .  '&response=' . urlencode($captcha)));

if ($reCAPTCHA->score <= 0.5)
{
    die("You are a bot!");
}

$SQL = "SELECT * FROM `users` WHERE `username` = ?";

//Prepares the SQL statement for execution.
$stmt = mysqli_prepare($connect, $SQL);

//Binds the username to the prepared statement as parameters.
mysqli_stmt_bind_param($stmt, 's', $username);

//Executes the prepared query.
mysqli_stmt_execute($stmt);

//Gets the result set from the prepared statement.
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) == 1)
{
    $USER = mysqli_fetch_assoc($result);

    if (password_verify($password, $USER['password']))
    {
        // echo "Welcome to the system " . $USER['firstName'];

        session_start();

        $_SESSION['userID'] = $USER['userID'];
        $_SESSION['firstName'] = $USER['firstName'];
        $_SESSION['lastName'] = $USER['lastName'];

        // header("Location: ../dashboard.php");

        echo "true";

        exit;
    }
}

die("Invalid username or password.");