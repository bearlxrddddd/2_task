<?php
require '../db_connect/db_connect.php';

$id = $_GET['id'] ?? 0;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_status = $_POST['status'];

    try {
        $pdo->beginTransaction();

        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id FOR UPDATE");
        $stmt->execute(['id' => $id]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            throw new Exception('Заказ не найден');
        }

        if ($new_status === 'отменён' && $order['status'] !== 'отменён') {
            $stmt = $pdo->prepare("SELECT * FROM positions_order WHERE order_id = :id");
            $stmt->execute(['id' => $id]);
            $positions = $stmt->fetchAll(PDO::FETCH_ASSOC);

            foreach ($positions as $position) {
                $stmt = $pdo->prepare("UPDATE products SET quantity_in_storage = quantity_in_storage + :qty 
                                       WHERE id = :id");
                $stmt->execute(['qty' => $position['quantity'], 'id' => $position['product_id']]);
            }
        }

        $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
        $stmt->execute(['status' => $new_status, 'id' => $id]);

        $pdo->commit();
        header("Location: /views/view_orders.php");
        exit;
    } catch (Exception $e) {
        $pdo->rollBack();
        $error = $e->getMessage();
    }
}

$stmt = $pdo->prepare("SELECT * FROM orders WHERE id = :id");
$stmt->execute(['id' => $id]);
$order = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$order) {
    exit('Заказ не найден');
}

$statuses = ['новый', 'в обработке', 'доставлен', 'отменён'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Изменить статус заказа</title>
</head>
<body>
    <h1>Изменить статус заказа №<?php echo $order['id'] ?></h1>
    <a href="/views/view_orders.php">Назад</a>

    <?php if (!empty($error)) : ?>
        <p><?php echo htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <form method="POST">
        <label>Статус:
            <select name="status" required>
                <?php foreach ($statuses as $s) : ?>
                    <option value="<?php echo $s ?>" <?php if ($order['status'] === $s) echo 'selected'; ?>>
                        <?php echo $s ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label><br>
        <button type="submit">Сохранить</button>
    </form>
</body>
</html>