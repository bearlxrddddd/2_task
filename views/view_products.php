<?php
require '../db_connect/db_connect.php';

$categorie_id = $_GET['categorie_id'] ?? '';
$in_stock = $_GET['in_stock'] ?? '';
$price_from = $_GET['price_from'] ?? '';
$price_to = $_GET['price_to'] ?? '';

$sql = "SELECT products.*, categories.title AS categorie_title 
        FROM products 
        LEFT JOIN categories ON products.categorie_id = categories.id 
        WHERE 1=1";
$params = [];

if ($categorie_id !== '') {
    $sql .= " AND products.categorie_id = :categorie_id";
    $params['categorie_id'] = $categorie_id;
}
if ($in_stock !== '') {
    $sql .= " AND products.quantity_in_storage > 0";
}
if ($price_from !== '') {
    $sql .= " AND products.price >= :price_from";
    $params['price_from'] = $price_from;
}
if ($price_to !== '') {
    $sql .= " AND products.price <= :price_to";
    $params['price_to'] = $price_to;
}

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/css/view_products.css">
    <title>Продукты</title>
</head>
<body>
    <h1>Продукты</h1>
    <a href="/">Назад</a><br>
    <a href="/add/add_products.php">Добавить продукт</a>

    <h2>Фильтры</h2>
    <form method="GET">
        <label>Категория:
            <select name="categorie_id">
                <option value="">Все</option>
                <?php foreach ($categories as $categorie) : ?>
                    <option value="<?php echo $categorie['id'] ?>" <?php if ($categorie_id == $categorie['id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($categorie['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>
        <label>Цена от: <input type="number" name="price_from" value="<?php echo htmlspecialchars($price_from) ?>"></label>
        <label>Цена до: <input type="number" name="price_to" value="<?php echo htmlspecialchars($price_to) ?>"></label>
        <label><input type="checkbox" name="in_stock" value="1" <?php if ($in_stock !== '') echo 'checked'; ?>> Только в наличии</label>
        <button type="submit">Фильтровать</button>
    </form>

    <table>
        <tr>
            <th class="tables_element">ID</th>
            <th class="tables_element">Имя</th>
            <th class="tables_element">Категория</th>
            <th class="tables_element">Стоимость</th>
            <th class="tables_element">Остаток</th>
            <th class="tables_element">Действия</th>
        </tr>
        <?php foreach ($products as $product) : ?>
        <tr>
            <th class="tables_element"><?php echo $product['id'] ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($product['title']) ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($product['categorie_title'] ?? '—') ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($product['price']) ?></th>
            <th class="tables_element"><?php echo htmlspecialchars($product['quantity_in_storage']) ?></th>
            <th class="tables_element">
                <a href="/update/update_product.php?id=<?php echo $product['id'] ?>">Изменить</a>
                <a href="/delete/delete_product.php?id=<?php echo $product['id'] ?>" onclick="alert('Подтвердить удаление?')" >Удалить</a>
            </th>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>