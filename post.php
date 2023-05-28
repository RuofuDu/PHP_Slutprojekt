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

// Add new comment form
echo '<form method="post" action="new_post.php?topic_id=' . $_GET['topic_id'] . '">
        <input type="hidden" name="topic_id" value="' . $_GET['topic_id'] . '">
        <input type="submit" value="New Post" name="new_post" class="btn">
      </form>';

// Database connection code
require_once 'db_connection.php';

// Retrieve topic ID from the query string
$topicId = $_GET['topic_id'];

// Retrieve posts for the specified topic from the "post" table
$query = "SELECT p.content, p.creation_time, p.likes, u.email
          FROM post p
          INNER JOIN user u ON p.creator = u.id
          WHERE p.topic_id = ?
          ORDER BY p.creation_time DESC";

$stmt = $conn->prepare($query);
$stmt->bind_param("i", $topicId);
$stmt->execute();
$result = $stmt->get_result();

// Display the posts in a table format
echo '<table>
        <thead>
          <tr>
            <th>Author</th>
            <th>Content</th>
            <th>Creation Time</th>
            <th>Likes</th>
          </tr>
        </thead>
        <tbody>';

while ($row = $result->fetch_assoc()) {
    echo '<tr class="result">
            <td>' . $row['email'] . '</td>
            <td>' . $row['content'] . '</td>
            <td>' . $row['creation_time'] . '</td>
            <td>' . $row['likes'] . '</td>
          </tr>';
}

echo '</tbody>
      </table>';

$stmt->close();

// Close the database connection
mysqli_close($conn);
?>
