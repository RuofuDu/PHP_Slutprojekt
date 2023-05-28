<?php
echo "I'm here: signup.php <br>";
require_once 'db_connection.php';

session_start();

// Retrieve the stored email and hashed password from session
$email = $_SESSION['session_email'] ?? '';
$hashedPassword = $_SESSION['session_password'] ?? '';

// Clear the session variables
unset($_SESSION['session_email']);
unset($_SESSION['session_password']);

// Process the email and hashed password
// Here you can perform further validation, database operations, etc.

// Example: Display the email and hashed password
echo "Email: " . $email . "<br>";
echo "Hashed Password: " . $hashedPassword;

// Prepare the SQL statement
$stmt = $conn->prepare("INSERT INTO user (email, password) VALUES (?, ?)");
$stmt->bind_param("ss", $email, $hashedPassword);

// Execute the statement
if ($stmt->execute()) {
    // Redirect to a success page using the PRG pattern
    echo "stmt executed";
    header("Location: topic.php");
    exit();
} else {
    // Redirect to an error page using the PRG pattern
    header("Location: error.php");
    exit();
}

?>
