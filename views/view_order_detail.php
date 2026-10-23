<?php
require '../db_connect/db_connect.php';

$id = $_GET['id'] ?? 0;

$stmt = $pdo->prepare("SELECT orders.*, clients.name AS client_name, clients.email, clients.tel 
                       FROM orders 
                       LEFT JOIN clients ON orders.client_id = clients.id 
                       WHERE orders.id = :id");
$stmt->execute(['id' => $id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

$stmt = $pdo->prepare("SELECT positions_order.*, products.title AS product_title 
                FROM positions_order 
                LEFT JOIN products ON positions_order.product_id = products.id 
                WHERE positions_order.order_id = :id");
$stmt->execute(['id' => $id]);
$positions = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/view_order_detail.css">
    <title>Заказ №<?php echo $order['id'] ?></title>
</head>
<body>
    <a href="/views/view_orders.php">Назад</a>

    <h1>Заказ №<?php echo $order['id'] ?></h1>
    <hr>
    <p>Клиент: <?php echo htmlspecialchars($order['client_name']) ?></p>
    <hr>
    <p>Email: <?php echo htmlspecialchars($order['email']) ?></p>
    <hr>
    <p>Телефон: <?php echo htmlspecialchars($order['tel']) ?></p>
    <hr>
    <p>Дата: <?php echo htmlspecialchars($order['data']) ?></p>
    <hr>
    <p>Статус: <?php echo htmlspecialchars($order['status']) ?></p>
    <hr>
    <p>Общая сумма: <?php echo htmlspecialchars($order['price']) ?></p>
    <hr>
    <h2>Товары в заказе:</h2>
    <table>
        <div class="table_order_name">
        <tr>
            <th class="tables_element">Товар</th>
            <th class="tables_element">Количество</th>
            <th class="tables_element">Цена</th>
            <th class="tables_element">Сумма</th>
        </tr>
    </div>
        <?php foreach ($positions as $position) : ?>
        <tr>
            <th class="tables_element"><?php echo htmlspecialchars($position['product_title'] ?? '—') ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($position['quantity']) ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($position['price']) ?></th>
            <th class="tables_element"><?php echo $position['quantity'] * $position['price'] ?></th>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>