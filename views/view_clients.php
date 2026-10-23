<?php
require '../db_connect/db_connect.php';
$stmt = $pdo->query("SELECT * FROM clients");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Клиенты</title>
</head>
<body>
    <h1>Клиенты</h1>
    <a href="/">Назад</a><br>
    <a href="/add/add_clients.php">Добавить клиента</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Имя</th>
            <th>Email</th>
            <th>Телефон</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($clients as $client) : ?>
        <tr>
            <th><?php echo $client['id'] ?></th>
            <th><?php echo htmlspecialchars($client['name']) ?></th>
            <th><?php echo htmlspecialchars($client['email']) ?></th>
            <th><?php echo htmlspecialchars($client['tel']) ?></th>
            <th>
                <a href="/update/update_client.php?id=<?php echo $client['id'] ?>">Изменить</a>
                <a href="/delete/delete_client.php?id=<?php echo $client['id'] ?>"  onclick="alert('Подтвердить удаление?')">Удалить</a>
            </th>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>