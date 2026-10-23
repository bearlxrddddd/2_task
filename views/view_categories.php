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
    <link rel="stylesheet" href="/css/view_categories.css">
</head>
<body>
    <h1>Категории</h1>
    <a href="/">Назад</a><br>
    <a href="/add/add_categories.php">Добавить категорию</a>
    <table>
        <tr>
            <th class="tables_element">ID</th>
            <th class="tables_element">Название</th>
            <th class="tables_element">Действия</th>
        </tr>
        <?php foreach ($categories as $categorie) : ?>
        <tr>
            <th class="tables_element"><?php echo $categorie['id'] ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($categorie['title']) ?></th>
            <th class="tables_element">
                <a href="/delete/delete_categorie.php?id=<?php echo $categorie['id'] ?>"  onclick="alert('Подтвердить удаление?')">Удалить</a>
            </th>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>