<?php
require __DIR__ . '/../db_connect/db_connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $client_id = (int)$_POST['client_id'];
    $data = $_POST['data'];
    $status = $_POST['status'];
    $selected_ids = $_POST['product_id'] ?? [];
    $quantities = $_POST['quantity'] ?? [];

    try {
        $pdo->beginTransaction();

        $total = 0;
        $positions = [];

        foreach ($selected_ids as $product_id) {
            $product_id = (int)$product_id;
            $qty = (int)($quantities[$product_id] ?? 0);

            if ($qty <= 0) {
                continue;
            }

            $stmt = $pdo->prepare("SELECT * FROM products WHERE id = :id FOR UPDATE");
            $stmt->execute(['id' => $product_id]);
            $product = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$product) {
                throw new Exception("Товар с ID $product_id не найден");
            }
            if ($product['quantity_in_storage'] < $qty) {
                throw new Exception("Недостаточно товара: " . $product['title']);
            }

            $sum = $product['price'] * $qty;
            $total += $sum;
            $positions[] = [
                'product_id' => $product_id,
                'quantity' => $qty,
                'price' => $product['price']
            ];
        }

        if (empty($positions)) {
            throw new Exception("Добавьте хотя бы один товар с количеством > 0");
        }

        $stmt = $pdo->prepare("INSERT INTO orders (price, status, data, client_id) 
        VALUES (:price, :status, :data, :client_id)");
        $stmt->execute([
            'price' => $total,
            'status' => $status,
            'data' => $data,
            'client_id' => $client_id
        ]);
        $order_id = $pdo->lastInsertId();

        foreach ($positions as $position) {
            $stmt = $pdo->prepare("INSERT INTO positions_order (order_id, product_id, quantity, price) 
            VALUES (:order_id, :product_id, :quantity, :price)");
            $stmt->execute([
                'order_id' => $order_id,
                'product_id' => $position['product_id'],
                'quantity' => $position['quantity'],
                'price' => $position['price']
            ]);

            $stmt = $pdo->prepare("UPDATE products SET quantity_in_storage = quantity_in_storage - :qty 
            WHERE id = :id");
            $stmt->execute(['qty' => $position['quantity'], 'id' => $position['product_id']]);
        }

        $pdo->commit();
        header("Location:/views/view_orders.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}

$stmt = $pdo->query("SELECT * FROM clients");
$stmt->execute();
$clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmt = $pdo->query("SELECT * FROM products WHERE quantity_in_storage > 0");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

$statuses = ['новый', 'в обработке', 'доставлен', 'отменён'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Добавить заказ</title>
</head>
<body>
    <h1>Добавить заказ</h1>
    <a href="/views/view_orders.php">Назад</a>

    <?php if (!empty($error)) : ?>
        <p><?php echo htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST" action="/add/add_orders.php">
        <label>Клиент:
            <select name="client_id" required>
                <?php foreach ($clients as $client) : ?>
                    <option value="<?php echo $client['id'] ?>">
                        <?php echo htmlspecialchars($client['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>

        <label>Дата: <input type="date" name="data" required></label><br>

        <label>Статус:
            <select name="status" required>
                <?php foreach ($statuses as $s) : ?>
                    <option value="<?php echo $s ?>"><?php echo $s ?></option>
                <?php endforeach; ?>
            </select>
        </label><br>

        <h2>Товары</h2>
        <p>Отметьте галочкой нужные товары и укажите количество.</p>

        <table>
            <tr>
                <th>Выбрать</th>
                <th>Товар</th>
                <th>Цена</th>
                <th>Остаток</th>
                <th>Количество</th>
            </tr>
            <?php foreach ($products as $product) : ?>
            <tr>
                <th>
                    <input type="checkbox" name="product_id[]" value="<?php echo $product['id'] ?>">
                </th>
                <th><?php echo htmlspecialchars($product['title']) ?></th>
                <th><?php echo htmlspecialchars($product['price']) ?></th>
                <th><?php echo htmlspecialchars($product['quantity_in_storage']) ?></th>
                <th>
                    <input type="number" 
                    name="quantity[<?php echo $product['id'] ?>]" 
                    value="0" 
                    min="0" 
                    max="<?php echo $product['quantity_in_storage'] ?>">
                </th>
            </tr>
            <?php endforeach; ?>
        </table>

        <br>
        <button type="submit">Оформить заказ</button>
    </form>
</body>
</html>