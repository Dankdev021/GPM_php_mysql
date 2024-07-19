<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin']);

$pageTitle = 'Usuários';
$customCSS = ['users/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/User.php';

$pdo = db_connect();
$userModel = new User($pdo);
$users = $userModel->getAll();
?>

<div class="container mt-5">
    <h2 class="text-center">Gerenciar Usuários</h2>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($users as $user): ?>
                <tr>
                    <td><?php echo htmlspecialchars($user['id']); ?></td>
                    <td><?php echo htmlspecialchars($user['username']); ?></td>
                    <td><?php echo htmlspecialchars($user['role']); ?></td>
                    <td>
                        <form action="../../controllers/UserController.php" method="POST" style="display:inline-block;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php include '../footer.php'; ?>
