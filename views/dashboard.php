<!-- views/dashboard.php -->

<?php
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: login.php');
    exit;
}

$user = $_SESSION['user'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <h2>Dashboard</h2>
        <p>Bem-vindo, <?php echo htmlspecialchars($user['username']); ?>!</p>
        <p><a href="../public/logout.php" class="btn btn-danger">Logout</a></p>
    </div>
</body>
</html>
