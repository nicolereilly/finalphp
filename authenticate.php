<?php

session_start();

// TODO: use www.w3schools.com/php/php_form_validation.asp to validate the
// form data; NOTE: this is done in newUser.php also; Hint: DRY
include './validate.php';

// create variables for the input form data
$endUser = $userPass = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $endUser = test_input($_POST["enduser"]);
    $userPass = test_input($_POST["userpass"]);
} else {
    // attempt to use GET??
    header("location:index.php");
    exit;
}

// TODO: query the database for the input user's credentials
// if not found, send them back to the homepage
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "programming";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// all we need from the database is the hashed password
//$hashedPassword = "";
$sql = "SELECT password FROM users WHERE username = '$endUser'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while ($row = $result->fetch_assoc()) {
        $hashedPassword = $row["password"];
    }
} else {
    $hashedPassword = '';
    $_SESSION['error'] = "invalid username";
    $conn->close();
    header("location:index.php");
    exit;
}



// TODO: authenticate the user using password_verify
// if the input password is valid, put the user in the SESSION 
// (using the key "username") and clear the "error" in the session (just to
// be sure)
// otherwise, put "invalid username or password" in the session using 
// the "error" key
if (password_verify($userPass, $hashedPassword)) {
    $_SESSION['username'] = $endUser;
    unset($_SESSION['error']);
} else {
    $_SESSION['error'] = "invalid username or password";
}

// TODO: close the database connection
$conn->close();

// TODO: redirect to index
header("location:index.php");
