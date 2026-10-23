<?php
require __DIR__ . '/../db_connect/db_connect.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM positions_order WHERE product_id = :id");
$stmt->execute(['id' => $id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    exit('Нельзя удалить товар: он входит в заказы. <a href="/views/view_products.php">Назад</a>');
}

$stmt = $pdo->prepare("DELETE FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location: /views/view_products.php");
exit;