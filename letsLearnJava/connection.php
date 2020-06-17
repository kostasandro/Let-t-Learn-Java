<?php

function initDbConnection(){
    //μεταβλητές για την σύνδεση με την βάση
    $servername = "localhost";
    $username = "root";
    $dbpassword = "";
    $dbname = "learnjava";


    // Δημιουργία connection
    $conn = mysqli_connect($servername, $username, $dbpassword, $dbname);
    mysqli_set_charset($conn,"utf8");

    // Έλεγχος connection
    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

?>