<?php
require_once '../config/Config.php';
require_once '../classes/Material.php';

$pdo = db_connect();
$materialModel = new Material($pdo);
$materials = $materialModel->getAll();

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="materials.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID', 'Nome', 'Descrição', 'Quantidade', 'Preço']);

foreach ($materials as $material) {
    fputcsv($output, [
        $material['id'],
        $material['name'],
        $material['description'],
        $material['quantity'],
        number_format($material['price'], 2, ',', '.')
    ]);
}

fclose($output);
exit;
?>
