<?php

$selected_state = $_GET['state'];



$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ajax_demo";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);
    $sql = "SELECT city_name FROM cities WHERE state_name = '$selected_state'";
    //echo $sql;
    $result = $conn->query($sql);
    
    if ($result->num_rows > 0) {
        echo "<select>";
        // output data of each row
        while ($row = $result->fetch_assoc()) {
            echo "<option>" . $row["city_name"] . "</option>";
        }echo "</select>";
    } 
    $conn->close();
} catch (Exception $ex) {
    error_log("Connection failed");
    echo("Contact IT Department");
    exit;
}








?>

