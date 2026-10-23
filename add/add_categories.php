<?php 
require '../db_connect/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title']
    ];
    $sql = "INSERT INTO categories (title) VALUES (:title)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($data);
    header("Location: /views/view_categories.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить категорию</title>
</head>
<body>
    <h1>Добавить категорию</h1>
    <a href="/views/view_categories.php">Назад</a>
    <form method="POST">
        <label>Название: <input type="text" name="title" required></label><br>
        <button type="submit">Добавить</button>
    </form>
</body>
</html>