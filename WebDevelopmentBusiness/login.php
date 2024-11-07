<?php
// Start the session at the top of the script
session_start();

// Enable error reporting for debugging (remove in production)
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Database credentials (make sure these are correct)
$host = 'localhost';
$db = 'web_development_business'; // Replace this with your actual database name
$user = 'root'; // Default user for XAMPP
$pass = ''; // Default password for XAMPP is empty

// Create a new MySQL connection using the improved OOP approach
$conn = new mysqli($host, $user, $pass, $db);

// Check for a successful connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// If the user is already logged in, redirect to the dashboard
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data and sanitize it
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Prepare SQL statement to prevent SQL injection
    $stmt = $conn->prepare("SELECT id, password FROM users WHERE email = ?");
    $stmt->bind_param("s", $email); // 's' is for string parameter
    $stmt->execute();
    $stmt->store_result();

    // If user exists with that email
    if ($stmt->num_rows > 0) {
        // Bind result variables
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();

        // Check if the entered password matches the hashed password in the database
        if (password_verify($password, $hashed_password)) {
            // Password is correct, set session variable
            $_SESSION['user_id'] = $user_id;
            header("Location: dashboard.php"); // Redirect to the dashboard after successful login
            exit();
        } else {
            // Incorrect password
            $error_message = "Invalid password. Please try again.";
        }
    } else {
        // No user found with that email
        $error_message = "No user found with that email address.";
    }

    // Close statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>

    <!-- Display error message if any -->
    <?php if (isset($error_message)): ?>
        <p style="color:red;"><?php echo $error_message; ?></p>
    <?php endif; ?>

    <!-- Login Form -->
    <form method="POST" action="login.php">
        <label for="email">Email:</label>
        <input type="email" name="email" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" required>
        <br>
        <button type="submit">Login</button>
    </form>

    <p>Don't have an account? <a href="register.php">Register here</a></p>
</body>
</html>
