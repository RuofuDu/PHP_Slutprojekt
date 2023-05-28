 <?php
session_start();

// uppdaterar en databas och genererar sedan dynamiskt en html-sida

header('Content-type: text/html');
include ("index.html");
echo "I'm here: index.php <br>";

// Sanitize and filter user input
$email = $_POST['email'] ?? '';
$password = $_POST['pass'] ?? '';
$email = filter_var($email, FILTER_SANITIZE_EMAIL);

// Validate the email
if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    // Invalid email, empty the "email" field
    $email = '';
}

if (!empty($password) && strlen($password) < 6) {
    // Invalid password, empty the "password" field
    $password = '';
}

if (!empty($email) && !empty($password)) {
    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Store the email and hashed password in session
    $_SESSION['session_email'] = $email;
    $_SESSION['session_password'] = $hashedPassword;
}
else {
    echo '<script>alert("Invalid email address, or password is too short (length < 6).");</script>';
    exit();
}

// Check if the sign-up form is submitted
if (isset($_POST['signup'])) {
    // Redirect to the sign-up page
    echo "I'm here: index.php, signup <br>";
    header("Location: signup.php");
}

// Check if the login form is submitted
if (isset($_POST['login'])) {
    // Retrieve the hashed password from the database for the given email
    require_once 'db_connection.php';
    $query = "SELECT password FROM user WHERE email = ?";
    $stmt = $conn->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        // Verify the password
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        if (password_verify($password, $hashedPassword)) {
            // Login successful, redirect to topic.php
            header("Location: topic.php");
            exit;
        }
    }

    // Invalid email or password, display an error message
    echo '<script>alert("Incorrect email or password.");</script>';
}


?> 