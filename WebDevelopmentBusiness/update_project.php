<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = $_POST['project_id'];
    $developer_id = $_POST['developer_id'];
    $project_name = $_POST['project_name'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    // Update the project in the database
    $stmt = $pdo->prepare("UPDATE projects SET developer_id = ?, project_name = ?, start_date = ?, end_date = ? WHERE project_id = ?");
    $stmt->execute([$developer_id, $project_name, $start_date, $end_date, $project_id]);

    // Update the project and set the last_updated timestamp
    $stmt = $pdo->prepare("UPDATE projects SET project_name = ?, last_updated = CURRENT_TIMESTAMP WHERE project_id = ?");
    $stmt->execute([$project_name, $project_id]);

    // Redirect to view projects page after updating
    header("Location: view_projects.php");
    exit();
}

$project_id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM projects WHERE project_id = ?");
$stmt->execute([$project_id]);
$project = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Project</title>
</head>
<body>
    <h1>Update Project</h1>
    <form method="POST">
        <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
        <label for="developer_id">Developer ID:</label>
        <input type="number" name="developer_id" value="<?php echo $project['developer_id']; ?>" required>
        <br>
        <label for="project_name">Project Name:</label>
        <input type="text" name="project_name" value="<?php echo $project['project_name']; ?>" required>
        <br>
        <label for="start_date">Start Date:</label>
        <input type="date" name="start_date" value="<?php echo $project['start_date']; ?>" required>
        <br>
        <label for="end_date">End Date:</label>
        <input type="date" name="end_date" value="<?php echo $project['end_date']; ?>" required>
        <br>
        <button type="submit">Update Project</button>
    </form>
    <a href="view_projects.php">View Projects</a>
</body>
</html>