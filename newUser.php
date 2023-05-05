<?php session_start();

include_once './validate.php';

// define variables and set to empty values
$form_username = $pwd = $repeat = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $form_username = test_input($_POST["form_username"]);
  $pwd = test_input($_POST["pwd"]);
  $repeat = test_input($_POST["repeat"]);
}



// TODO: make sure that pwd and repeat match.  If they don't match, send the 
// user back to the form with an appropriate error message.
if ($pwd != $repeat) {
    $_SESSION['error'] = "passwords don't match";
    header("location: register.php");
    exit;
}

// TODO: make sure that the new user is not already in the database.  If the
// new username has already been used, send the user back to the form with an 
// appropriate error message.
// HINT: SELECT * FROM users where username = '$form_username'
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

$sql = "SELECT * FROM users where username = '$form_username'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $_SESSION['error'] = "username already in use";
    header("location: register.php");
    $conn->close();
    exit;
}


// TODO: insert the new user into the database
// HINT: INSERT INTO users(username, password) VALUES('$form_username', '$hashed_password')
// HINT: you'll need to call password_hash to get $hashed_password
$hashed_password = password_hash($pwd, PASSWORD_DEFAULT);

$sql = "INSERT INTO users(username, password) VALUES('$form_username', '$hashed_password')";

if ($conn->query($sql) != TRUE) {
    // log file "Error: " . $sql . "<br>" . $conn->error;
    $_SESSION['error'] = "contact administrator";
    header("location: register.php");
    $conn->close();
    exit;
}

// TODO: close the database connection
$conn->close();

// if we made it here, we have a new user; send them to the homepage
header("location:index.php");

