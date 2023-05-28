<?php
// db_connection.php

// Database configuration
$dbHost = 'localhost';
$dbUser = 'root';
//$dbPass = 'rf_Du@23_aVa';
$dbPass = '';
$dbName = 'forum';

// Create a database connection
$conn = new mysqli($dbHost, $dbUser, $dbPass, $dbName);

// Check connection
if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
    echo '<script>alert("Sorry. We are having technical issues. Please login later.");</script>';
    exit();
}
