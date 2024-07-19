<?php
session_start();
require_once '../../config/auth.php';

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin', 'vendedor', 'cliente']);

$pageTitle = 'Criar Ordem de Serviço';
$customCSS = ['services/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/User.php';

$pdo = db_connect();
$userModel = new User($pdo);
$mechanics = $userModel->getUsersByRole('vendedor');
?>

<div class="container mt-5">
    <h2 class="text-center">Criar Ordem de Serviço</h2>
    <form action="../../controllers/ServiceOrderController.php" method="POST">
        <input type="hidden" name="action" value="create_service_order">
        <div class="form-group">
            <label for="customer_id">Cliente</label>
            <input type="text" class="form-control" id="customer_id" name="customer_id" value="<?php echo $_SESSION['user']['id']; ?>" readonly>
        </div>
        <div class="form-group">
            <label for="mechanic_id">Mecânico</label>
            <select class="form-control" id="mechanic_id" name="mechanic_id" required>
                <option value="" disabled selected>Selecione um Mecânico</option>
                <?php foreach ($mechanics as $mechanic): ?>
                    <option value="<?php echo $mechanic['id']; ?>"><?php echo htmlspecialchars($mechanic['username']); ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label for="service_type">Tipo de Serviço</label>
            <input type="text" class="form-control" id="service_type" name="service_type" required>
        </div>
        <div class="form-group">
            <label for="vehicle_model">Modelo do Veículo</label>
            <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" required>
        </div>
        <div class="form-group">
            <label for="vehicle_license_plate">Placa do Veículo</label>
            <input type="text" class="form-control" id="vehicle_license_plate" name="vehicle_license_plate" required>
        </div>
        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea class="form-control" id="description" name="description" rows="5" required></textarea>
        </div>
        <div class="form-group">
            <!-- <label for="estimated_cost">Custo Estimado</label> -->
            <input type="hidden" class="form-control" id="estimated_cost" name="estimated_cost" value="0" step="0.01" required>
        </div>
        <button type="submit" class="btn btn-primary">Criar Ordem de Serviço</button>
    </form>
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

<?php include '../footer.php'; ?>
