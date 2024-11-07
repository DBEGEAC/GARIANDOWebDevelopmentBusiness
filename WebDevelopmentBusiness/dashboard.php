<?php
// Start the session and check if the user is logged in
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Get user details (like username) from session
$user_id = $_SESSION['user_id'];

// Database connection
$host = 'localhost';
$db = 'web_development_business';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}

// Fetch user details (e.g., username, first name, last name) from the database
$stmt = $pdo->prepare("SELECT username, first_name, last_name FROM users WHERE id = ?");
$stmt->execute([$user_id]);
$user_data = $stmt->fetch();

if ($user_data) {
    $username = $user_data['username'];
    $first_name = $user_data['first_name'];
    $last_name = $user_data['last_name'];
} else {
    // In case the user is not found, log them out
    session_unset();
    session_destroy();
    header("Location: login.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f4f4f4;
        }
        h1 {
            color: #333;
        }
        .welcome-message {
            font-size: 20px;
            margin-bottom: 20px;
        }
        .nav-links {
            margin-top: 30px;
            font-size: 18px;
        }
        .nav-links a {
            color: #0066cc;
            text-decoration: none;
            margin-right: 20px;
        }
        .nav-links a:hover {
            text-decoration: underline;
        }
        .logout-btn {
            color: white;
            background-color: #e60000;
            border: none;
            padding: 10px 20px;
            font-size: 16px;
            cursor: pointer;
        }
        .logout-btn:hover {
            background-color: #cc0000;
        }
    </style>
</head>
<body>

    <h1>Welcome to your Dashboard!</h1>
    <p class="welcome-message">Hello, <?php echo htmlspecialchars($first_name . ' ' . $last_name); ?> (<?php echo htmlspecialchars($username); ?>)</p>

    <div class="nav-links">
        <a href="view_projects.php">View Projects</a>
        <a href="add_project.php">Add New Project</a>
        <a href="update_project.php">Update Projects</a>
    </div>
    
    <!-- Logout Button -->
     <a href="logout.php">
        <button type="button">Logout</button>
    </a>

    <form action="logout.php" method="POST">
        <button type="submit" class="logout-btn">Logout</button>
    </form>

</body>
</html>
