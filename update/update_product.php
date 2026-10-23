<?php
require '../db_connect/db_connect.php';

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'price' => $_POST['price'],
        'quantity_in_storage' => $_POST['quantity_in_storage'],
        'categorie_id' => (int)$_POST['categorie_id'],
        'id' => $id
    ];
    $sql = "UPDATE products SET title = :title, price = :price, 
            quantity_in_storage = :quantity_in_storage, categorie_id = :categorie_id 
            WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($data);
    header("Location: /views/view_products.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id");
$stmt->execute(['id' => $id]);
$product = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$product) {
    exit('Товар не найден');
}

$stmt = $pdo->query("SELECT * FROM categories");
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить продукт</title>
</head>
<body>
    <h1>Изменить продукт</h1>
    <a href="/views/view_products.php">Назад</a>
    <form method="POST">
        <label>Название: <input type="text" name="title" value="<?php echo htmlspecialchars($product['title']) ?>" required></label><br>
        <label>Цена: <input type="number" step="0.01" name="price" value="<?php echo htmlspecialchars($product['price']) ?>" required></label><br>
        <label>Остаток: <input type="number" name="quantity_in_storage" value="<?php echo htmlspecialchars($product['quantity_in_storage']) ?>" required></label><br>
        <label>Категория:
            <select name="categorie_id" required>
                <?php foreach ($categories as $categorie) : ?>
                    <option value="<?php echo $categorie['id'] ?>" <?php if ($categorie['id'] == $product['categorie_id']) echo 'selected'; ?>>
                        <?php echo htmlspecialchars($categorie['title']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">Сохранить</button>
    </form>
</body>
</html>