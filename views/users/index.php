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
    <div class="text-left mb-3">
        <a href="../../scripts/download_users.php" class="btn btn-success btn-sm" style="max-width: 200px;">Download dos Usuários</a>
    </div>
    <table class="table table-striped mt-4">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Role</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody id="usersBody">
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

    <!-- Paginação -->
    <nav aria-label="Navegação de página">
        <ul class="pagination justify-content-center" id="pagination">
            <!-- Paginação será gerada dinamicamente -->
        </ul>
    </nav>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const users = <?php echo json_encode($users); ?>;
    const itemsPerPage = 10;
    let currentPage = 1;

    function renderTable(page) {
        const start = (page - 1) * itemsPerPage;
        const end = start + itemsPerPage;
        const paginatedItems = users.slice(start, end);

        const tbody = document.getElementById('usersBody');
        tbody.innerHTML = '';

        paginatedItems.forEach(user => {
            const row = document.createElement('tr');

            row.innerHTML = `
                <td>${user.id}</td>
                <td>${user.username}</td>
                <td>${user.role}</td>
                <td>
                    <form action="../../controllers/UserController.php" method="POST" style="display:inline-block;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="${user.id}">
                        <button type="submit" class="btn btn-danger btn-sm">Deletar</button>
                    </form>
                </td>
            `;
            tbody.appendChild(row);
        });
    }

    function renderPagination() {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        const totalPages = Math.ceil(users.length / itemsPerPage);

        const prevPageItem = document.createElement('li');
        prevPageItem.className = 'page-item' + (currentPage === 1 ? ' disabled' : '');
        prevPageItem.innerHTML = `<a class="page-link" href="#" tabindex="-1">Anterior</a>`;
        prevPageItem.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                renderTable(currentPage);
                renderPagination();
            }
        });
        pagination.appendChild(prevPageItem);

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = 'page-item' + (i === currentPage ? ' active' : '');
            pageItem.innerHTML = `<a class="page-link" href="#">${i}</a>`;
            pageItem.addEventListener('click', function() {
                currentPage = i;
                renderTable(currentPage);
                renderPagination();
            });
            pagination.appendChild(pageItem);
        }

        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item' + (currentPage === totalPages ? ' disabled' : '');
        nextPageItem.innerHTML = `<a class="page-link" href="#">Próxima</a>`;
        nextPageItem.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                renderTable(currentPage);
                renderPagination();
            }
        });
        pagination.appendChild(nextPageItem);
    }

    renderTable(currentPage);
    renderPagination();
});
</script>

<?php include '../footer.php'; ?>
