<?php
include 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $project_id = $_POST['project_id'];

    // Delete the project from the database
    $stmt = $pdo->prepare("DELETE FROM projects WHERE project_id = ?");
    $stmt->execute([$project_id]);

    // Redirect to view projects page after deletion
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
    <title>Delete Project</title>
</head>
<body>
    <h1>Delete Project</h1>
    <p>Are you sure you want to delete the project: <strong><?php echo $project['project_name']; ?></strong>?</p>
    <form method="POST">
        <input type="hidden" name="project_id" value="<?php echo $project['project_id']; ?>">
        <button type="submit">Delete Project</button>
    </form>
    <a href="view_projects.php">Cancel</a>
</body>
</html>