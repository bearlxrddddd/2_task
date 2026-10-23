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
    <link rel="stylesheet" href="/css/view_clients.css">
</head>
<body>
    <h1>Клиенты</h1>
    <a href="/">Назад</a><br>
    <a href="/add/add_clients.php">Добавить клиента</a>
    <table>
        <tr>
            <th class="tables_element">ID</th>
            <th class="tables_element">Имя</th>
            <th class="tables_element">Email</th>
            <th class="tables_element">Телефон</th>
            <th class="tables_element">Действия</th>
        </tr>
        <?php foreach ($clients as $client) : ?>
        <tr>
            <th class="tables_element"><?php echo $client['id'] ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($client['name']) ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($client['email']) ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($client['tel']) ?></th>
            <th class="tables_element">
                <a href="/update/update_client.php?id=<?php echo $client['id'] ?>">Изменить</a>
                <a href="/delete/delete_client.php?id=<?php echo $client['id'] ?>"  onclick="alert('Подтвердить удаление?')">Удалить</a>
            </th>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>