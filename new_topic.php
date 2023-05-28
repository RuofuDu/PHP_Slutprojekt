<?php
session_start();
header('Content-type: text/html');
echo '<link rel="stylesheet" type="text/css" href="style.css">';

// Check if the user is not logged in
if (!isset($_SESSION['session_email']) || !isset($_SESSION['session_userid'])) {
    // User is not logged in, redirect them to the login page or display an error message
    echo "<h1>You haven't logged in</h1><hr>";
    exit();
}

echo "<h1>Welcome {$_SESSION['session_email']}</h1>";

// Add new topic form
echo '<form method="post" action="new_topic.php">
        <h3>Topic Heading</h3>
        <textarea name="heading" rows="5" cols="50"></textarea>
        <input type="submit" value="Add" name="add_topic" class="btn">
      </form>';

// Database connection code
require_once 'db_connection.php';

// Check if the add topic form is submitted
if (isset($_POST['add_topic'])) {
    // Retrieve the heading from the form
    $heading = $_POST['heading'];
    
    // Insert the new topic into the "topic" table
    $query = "INSERT INTO topic (heading, creator) VALUES (?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("si", $heading, $_SESSION['session_userid']);
    $stmt->execute();
    $stmt->close();
    
    // Redirect to the topic.php page or display a success message
    header("Location: topic.php");
    exit();
}

// Close the database connection
mysqli_close($conn);
?>
