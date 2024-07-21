<?php
require_once '../config/Config.php';
require_once '../classes/Order.php';

$pdo = db_connect();
$orderModel = new Order($pdo);
$sales = $orderModel->getAll();

header('Content-Type: text/csv');
header('Content-Disposition: attachment;filename=sales.csv');

$output = fopen('php://output', 'w');
fputcsv($output, ['Nome do Material', 'Quantidade', 'Preço', 'Data da Venda']);

foreach ($sales as $sale) {
    fputcsv($output, [
        htmlspecialchars($sale['material_name']),
        htmlspecialchars($sale['quantity']),
        htmlspecialchars($sale['material_price']),
        htmlspecialchars($sale['created_at'])
    ]);
}

fclose($output);
exit;
?>
