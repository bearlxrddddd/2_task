<?php
require '../db_connect/db_connect.php';

$status = $_GET['status'] ?? '';
$client_id = $_GET['client_id'] ?? '';
$date_from = $_GET['date_from'] ?? '';
$date_to = $_GET['date_to'] ?? '';

$sql = "SELECT orders.*, clients.name AS client_name 
        FROM orders 
        LEFT JOIN clients ON orders.client_id = clients.id 
        WHERE 1=1";
$params = [];

if ($status !== '') {
    $sql .= " AND orders.status = :status";
    $params['status'] = $status;
}
if ($client_id !== '') {
    $sql .= " AND orders.client_id = :client_id";
    $params['client_id'] = $client_id;
}
if ($date_from !== '') {
    $sql .= " AND orders.data >= :date_from";
    $params['date_from'] = $date_from;
}
if ($date_to !== '') {
    $sql .= " AND orders.data <= :date_to";
    $params['date_to'] = $date_to;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM clients");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statuses = ['новый', 'в обработке', 'доставлен', 'отменён'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Заказы</title>
</head>
<body>
    <h1>Заказы</h1>
    <a href="/">Назад</a><br>
    <a href="/add/add_orders.php">Добавить заказ</a>

    <h2>Фильтры</h2>
    <form method="GET">
        <label>Статус:
            <select name="status">
                <option value="">Все</option>
                <?php foreach ($statuses as $s) : ?>
                    <option value="<?php echo $s ?>" <?php if ($status === $s) echo 'selected'; ?>>
                        <?php echo $s ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Клиент:
            <select name="client_id">
                <option value="">Все</option>
                <?php foreach ($clients as $client) : ?>
                    <option value="<?php echo $client['id'] ?>" <?php if ($client_id == $client['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($client['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Дата от: <input type="date" name="date_from" value="<?php echo htmlspecialchars($date_from) ?>"></label>
        <label>Дата до: <input type="date" name="date_to" value="<?php echo htmlspecialchars($date_to) ?>"></label>
        <button type="submit">Фильтровать</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Клиент</th>
            <th>Дата</th>
            <th>Статус</th>
            <th>Сумма</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($orders as $order) : ?>
        <tr>
            <th><?php echo $order['id'] ?></th>
            <th><?php echo htmlspecialchars($order['client_name'] ?? '—') ?></th>
            <th><?php echo htmlspecialchars($order['data']) ?></th>
            <th><?php echo htmlspecialchars($order['status']) ?></th>
            <th><?php echo htmlspecialchars($order['price']) ?></th>
            <th>
                <a href="/views/view_order_detail.php?id=<?php echo $order['id'] ?>">Подробнее</a>
                <a href="/update/update_status_order.php?id=<?php echo $order['id'] ?>">Изменить статус</a>
            </th>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>