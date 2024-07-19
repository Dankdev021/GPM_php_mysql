<?php
session_start();
require_once '../../config/auth.php';

// var_dump($_GET['id']);
// die();

redirectIfNotLoggedIn();
checkAccess($_SESSION['user']['role'], ['admin', 'vendedor']);

$pageTitle = 'Editar Ordem de Serviço';
$customCSS = ['services/style.css'];
include '../header.php';

require_once '../../config/Config.php';
require_once '../../classes/ServiceOrder.php';
require_once '../../classes/User.php';

$pdo = db_connect();
$serviceOrderModel = new ServiceOrder($pdo);
$userModel = new User($pdo);

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}
$id = $_GET['id'];
$serviceOrder = $serviceOrderModel->getById($id);

if (!$serviceOrder) {
    header('Location: index.php');
    exit();
}

// Obter lista de mecânicos
$mechanics = $userModel->getUsersByRole('vendedor');
?>

<div class="container mt-5">
    <h2 class="text-center">Editar Ordem de Serviço</h2>
    <form action="../../controllers/ServiceOrderController.php" method="POST">
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="id" value="<?php echo htmlspecialchars($serviceOrder['id']); ?>">

        <div class="form-group">
            <label for="customer_name">Cliente</label>
            <input type="text" class="form-control" id="customer_name" value="<?php echo htmlspecialchars($serviceOrder['customer_name']); ?>" readonly>
        </div>

        <div class="form-group">
            <label for="mechanic_id">Mecânico</label>
            <select class="form-control" id="mechanic_id" name="mechanic_id" required>
                <?php foreach ($mechanics as $mechanic): ?>
                    <option value="<?php echo $mechanic['id']; ?>" <?php echo $serviceOrder['mechanic_id'] == $mechanic['id'] ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($mechanic['username']); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label for="service_type">Tipo de Serviço</label>
            <input type="text" class="form-control" id="service_type" name="service_type" value="<?php echo htmlspecialchars($serviceOrder['service_type']); ?>" required>
        </div>

        <div class="form-group">
            <label for="vehicle_model">Modelo do Veículo</label>
            <input type="text" class="form-control" id="vehicle_model" name="vehicle_model" value="<?php echo htmlspecialchars($serviceOrder['vehicle_model']); ?>" required>
        </div>

        <div class="form-group">
            <label for="vehicle_license_plate">Placa do Veículo</label>
            <input type="text" class="form-control" id="vehicle_license_plate" name="vehicle_license_plate" value="<?php echo htmlspecialchars($serviceOrder['vehicle_license_plate']); ?>" required>
        </div>

        <div class="form-group">
            <label for="description">Descrição</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo htmlspecialchars($serviceOrder['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label for="estimated_cost">Custo Estimado</label>
            <input type="number" class="form-control" id="estimated_cost" name="estimated_cost" value="<?php echo htmlspecialchars($serviceOrder['estimated_cost']); ?>" step="0.01" required>
        </div>

        <div class="form-group">
            <label for="status">Status</label>
            <select class="form-control" id="status" name="status" required>
                <option value="pendente" <?php echo $serviceOrder['status'] == 'pendente' ? 'selected' : ''; ?>>Pendente</option>
                <option value="concluido" <?php echo $serviceOrder['status'] == 'concluido' ? 'selected' : ''; ?>>Concluído</option>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
    </form>
</div>

<?php include '../footer.php'; ?>
