<?php
require_once '../config/Config.php';
require_once '../classes/User.php';

$pdo = db_connect();
$userModel = new User($pdo);
$users = $userModel->getAll();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="users.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Username', 'Role']);

foreach ($users as $user) {
    fputcsv($output, [
        $user['id'],
        $user['username'],
        $user['role']
    ]);
}

fclose($output);
exit;
?>
