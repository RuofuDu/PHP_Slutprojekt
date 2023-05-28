<?php
session_start();
header('Content-type: text/html');
echo '<link rel="stylesheet" type="text/css" href="style.css">';


// Check if the user is not logged in
if (!isset($_SESSION['session_email'])) {
    // User is not logged in, redirect them to the login page or display an error message
    echo "<h1>You haven't logged in</h1><hr>";
    exit();
}

echo "<h1>Welcome {$_SESSION['session_email']}</h1>";

// Add new topic form
echo '<form method="post" action="new_topic.php">
        <input type="submit" value="New Topic" name="new_topic" class="btn">
      </form>';

// Database connection code
require_once 'db_connection.php';

// Retrieve the user ID from the "user" table
$query = "SELECT id FROM user WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $_SESSION['session_email']);
$stmt->execute();
$stmt->bind_result($userId);
$stmt->fetch();
$stmt->close();

// Store the user ID in the session variable
$_SESSION['session_userid'] = $userId;



// Retrieve data from the database and sort by creation_time in descending order
$query = "SELECT topic.heading, topic.creation_time, topic.likes, user.email, topic.id
          FROM topic
          INNER JOIN user ON topic.creator = user.id
          ORDER BY topic.creation_time DESC";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) > 0) {
    // Display the table
    echo '<table>
            <thead>
              <tr>
                <th>Author</th>
                <th>Heading</th>
                <th>Creation Time</th>
                <th>Likes</th>
              </tr>
            </thead>
            <tbody>';

    $rowNum = 0; // Counter for row background color

    // Display the table rows with data
    while ($row = mysqli_fetch_assoc($result)) {
        echo '<tr class="result">
                <td>' . $row['email'] . '</td>
                <td><a href="post.php?topic_id=' . $row['id'] . '">' . htmlspecialchars($row['heading']) . '</a></td>
                <td>' . $row['creation_time'] . '</td>
                <td>' . $row['likes'] . '</td>
              </tr>';
    }

    echo '</tbody>
          </table>';
} else {
    echo "No topics found.";
}

// Close the database connection
mysqli_close($conn);
?>
