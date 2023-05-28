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

$topic_id=$_GET['topic_id'];

// Add new post form
echo '<form method="post" action="new_post.php?topic_id=' . $topic_id . '">
        <h3>Post Content</h3>
        <textarea name="content" rows="5" cols="50"></textarea>
        <input type="submit" value="Add" name="add_post" class="btn">
      </form>';

// Database connection code
require_once 'db_connection.php';

// Check if the add post form is submitted
if (isset($_POST['add_post'])) {
    // Retrieve the post content from the form
    $content = $_POST['content'];

    // Insert the new post into the "post" table
    $query = "INSERT INTO post (content, creator, topic_id) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("sii", $content, $_SESSION['session_userid'], $topic_id);
    $stmt->execute();
    $stmt->close();

    // Redirect to the post.php page or display a success message
    header("Location: post.php?topic_id=$topic_id");

    exit();
}

// Close the database connection
mysqli_close($conn);
?>
