<?php
require __DIR__ . '/../db_connect/db_connect.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM orders WHERE client_id = :id");
$stmt->execute(['id' => $id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    exit('Нельзя удалить клиента: у него есть заказы. <a href="/views/view_clients.php">Назад</a>');
}

$stmt = $pdo->prepare("DELETE FROM clients WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location:/views/view_clients.php");
exit;