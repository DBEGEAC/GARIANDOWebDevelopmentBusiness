<?php
// Start the session and check if the user is logged in
session_start();

// If the user is not logged in, redirect to the login page
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

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

// Fetch projects and user information from the database
$stmt = $pdo->prepare("SELECT p.*, u.first_name, u.last_name FROM projects p JOIN users u ON p.added_by = u.id");
$stmt->execute();
$projects = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Projects</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h1>Projects List</h1>
    <a href="add_project.php">Add New Project</a> <!-- Link to add new project -->
    <!-- Logout Button -->
     <a href="logout.php">
        <button type="button">Logout</button>
    </a>
    <table border="1">
        <thead>
            <tr>
                <th>Project ID</th>
                <th>Project Name</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Added By</th>
                <th>Last Updated</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (count($projects) > 0): ?>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($project['project_id']); ?></td>
                        <td><?php echo htmlspecialchars($project['project_name']); ?></td>
                        <td><?php echo htmlspecialchars($project['start_date']); ?></td>
                        <td><?php echo htmlspecialchars($project['end_date']); ?></td>
                        <td><?php echo htmlspecialchars($project['first_name'] . ' ' . $project['last_name']); ?></td>
                        <td><?php echo htmlspecialchars($project['last_updated']); ?></td>
                        <td>
                            <a href="update_project.php?id=<?php echo $project['project_id']; ?>">Update</a>
                            <form action="delete_project.php" method="POST" style="display:inline;">
                                <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
                                <button type="submit" onclick="return confirm('Are you sure you want to delete this project?');">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="7">No projects found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
