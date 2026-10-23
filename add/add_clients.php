<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'tel' => $_POST['tel']
    ];
    require '../db_connect/db_connect.php';
    $sql = "INSERT INTO clients (name, email, tel) VALUES (:name, :email, :tel)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($data);
    header("Location: /views/view_clients.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить клиента</title>
</head>
<body>
    <h1>Добавить клиента</h1>
    <a href="/views/view_clients.php">Назад</a>
    <form method="POST">
        <label>Имя: <input type="text" name="name" required></label><br>
        <label>Email: <input type="email" name="email" required></label><br>
        <label>Телефон: <input type="text" name="tel" required></label><br>
        <button type="submit">Добавить</button>
    </form>
</body>
</html>