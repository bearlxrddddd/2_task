<?php
require __DIR__ . '/../db_connect/db_connect.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM products WHERE categorie_id = :id");
$stmt->execute(['id' => $id]);
$count = $stmt->fetchColumn();

if ($count > 0) {
    exit('Нельзя удалить категорию: к ней привязаны товары. <a href="/views/view_categories.php">Назад</a>');
}

$stmt = $pdo->prepare("DELETE FROM categories WHERE id = :id");
$stmt->execute(['id' => $id]);

header("Location: /views/view_categories.php");
exit;