<?php
require '../db_connect/db_connect.php';

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'name' => $_POST['name'],
        'email' => $_POST['email'],
        'tel' => $_POST['tel'],
        'id' => $id
    ];
    $sql = "UPDATE clients SET name = :name, email = :email, tel = :tel WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    header("Location: /views/view_clients.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM clients WHERE id = :id");
$stmt->execute(['id' => $id]);
$client = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$client) {
    exit('Клиент не найден');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить клиента</title>
</head>
<body>
    <h1>Изменить клиента</h1>
    <a href="/views/view_clients.php">Назад</a>
    <form method="POST">
        <label>Имя: <input type="text" name="name" value="<?php echo htmlspecialchars($client['name']) ?>" required></label><br>
        <label>Email: <input type="email" name="email" value="<?php echo htmlspecialchars($client['email']) ?>" required></label><br>
        <label>Телефон: <input type="text" name="tel" value="<?php echo htmlspecialchars($client['tel']) ?>" required></label><br>
        <button type="submit">Сохранить</button>
    </form>
</body>
</html>