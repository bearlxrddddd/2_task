<?php
require '../db_connect/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'title' => $_POST['title'],
        'price' => $_POST['price'],
        'quantity_in_storage' => $_POST['quantity_in_storage'],
        'categorie_id' => (int)$_POST['categorie_id']
    ];
    $sql = "INSERT INTO products (title, price, quantity_in_storage, categorie_id) 
            VALUES (:title, :price, :quantity_in_storage, :categorie_id)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute($data);
    header("Location: /views/view_products.php");
    exit;
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
    <title>Добавить продукт</title>
</head>
<body>
    <h1>Добавить продукт</h1>
    <a href="/views/view_products.php">Назад</a>
    <form method="POST">
        <label>Название: <input type="text" name="title" required></label><br>
        <label>Цена: <input type="number" step="0.01" name="price" required></label><br>
        <label>Остаток: <input type="number" name="quantity_in_storage" required></label><br>
        <label>Категория:
            <select name="categorie_id" required>
                <?php foreach ($categories as $categorie) : ?>
                    <option value="<?php echo $categorie['id'] ?>"><?php echo htmlspecialchars($categorie['title']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">Добавить</button>
    </form>
</body>
</html>