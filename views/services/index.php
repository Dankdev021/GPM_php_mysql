<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['cliente']);

$pageTitle = 'Serviços';
$customCSS = ['services/style.css'];
include 'header.php';

require_once '../../config/Config.php';
require_once '../../classes/Service.php';

$pdo = db_connect();
$serviceModel = new Service($pdo);
$services = $serviceModel->getAll();
?>

<div class="container mt-5">
    <h2 class="text-center">Serviços Disponíveis</h2>
    <div class="row">
        <?php foreach ($services as $service): ?>
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title"><?php echo htmlspecialchars($service['name']); ?></h5>
                        <p class="card-text"><?php echo htmlspecialchars($service['description']); ?></p>
                        <p class="card-text"><strong>Preço: </strong>R$ <?php echo htmlspecialchars($service['price']); ?></p>
                        <button class="btn btn-primary" data-toggle="modal" data-target="#serviceModal" data-id="<?php echo $service['id']; ?>" data-name="<?php echo htmlspecialchars($service['name']); ?>">Contratar</button>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal de Contratação -->
<div class="modal fade" id="serviceModal" tabindex="-1" role="dialog" aria-labelledby="serviceModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="serviceModalLabel">Contratar Serviço</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="../../controllers/ServiceOrderController.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="action" value="hire">
                    <input type="hidden" name="service_id" id="modalServiceId">
                    <div class="form-group">
                        <label for="modalServiceName">Serviço</label>
                        <p id="modalServiceName"></p>
                    </div>
                    <div class="form-group">
                        <label for="seller_id">Vendedor</label>
                        <select name="seller_id" class="form-control" required>
                            <option value="" selected disabled>Selecione um Vendedor</option>
                            <?php
                            $stmt = $pdo->query("SELECT id, username FROM users WHERE role = 'vendedor'");
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)): ?>
                                <option value="<?php echo $row['id']; ?>"><?php echo htmlspecialchars($row['username']); ?></option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Fechar</button>
                    <button type="submit" class="btn btn-primary">Contratar</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var serviceButtons = document.querySelectorAll('.btn-primary[data-toggle="modal"]');
        
        serviceButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                var id = button.getAttribute('data-id');
                var name = button.getAttribute('data-name');

                var modalServiceId = document.getElementById('modalServiceId');
                var modalServiceName = document.getElementById('modalServiceName');

                modalServiceId.value = id;
                modalServiceName.textContent = name;
            });
        });
    });
</script>

<?php
$customJS = ['services/script.js'];
include 'footer.php';
?>
