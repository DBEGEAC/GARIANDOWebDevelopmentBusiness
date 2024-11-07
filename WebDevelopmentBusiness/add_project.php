<?php
// Start the session to access session variables
session_start();

// Check if the user is logged in (i.e., session variable 'user_id' exists)
if (!isset($_SESSION['user_id'])) {
    // If not logged in, redirect to the login page
    header("Location: login.php");
    exit();
}

include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $developer_id = $_POST['developer_id'];
    $project_name = $_POST['project_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Assuming 'user_id' is stored in the session when the user is logged in
    $added_by = $_SESSION['user_id'];

    // Insert the new project into the database
    $stmt = $pdo->prepare("INSERT INTO projects (developer_id, project_name, start_date, end_date, added_by) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$developer_id, $project_name, $start_date, $end_date, $added_by]);

    // Redirect to view projects page after adding the project
    header("Location: view_projects.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Project</title>
</head>
<body>
    <h1>Add New Project</h1>
    <form method="POST">
        <label for="developer_id">Developer ID:</label>
        <input type="number" name="developer_id" required>
        <br>
        <label for="project_name">Project Name:</label>
        <input type="text" name="project_name" required>
        <br>
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" required>
        <br>
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" required>
        <br>
        <button type="submit">Add Project</button>
    </form>
    <a href="view_projects.php">View Projects</a>
</body>
</html>
