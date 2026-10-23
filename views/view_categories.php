<?php
require '../db_connect/db_connect.php';
$stmt = $pdo->query("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Категории</title>
</head>
<body>
    <h1>Категории</h1>
    <a href="/">Назад</a><br>
    <a href="/add/add_categories.php">Добавить категорию</a>
    <table>
        <tr>
            <th>ID</th>
            <th>Название</th>
            <th>Действия</th>
        </tr>
        <?php foreach ($categories as $categorie) : ?>
        <tr>
            <th><?php echo $categorie['id'] ?></th>
            <th><?php echo htmlspecialchars($categorie['title']) ?></th>
            <th>
                <a href="/delete/delete_categorie.php?id=<?php echo $categorie['id'] ?>"  onclick="alert('Подтвердить удаление?')">Удалить</a>
            </th>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>