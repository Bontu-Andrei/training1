<?php

require_once 'common.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {
    $pdo = pdoConnectMysql();

    $orderId = (int) $_GET['id'];

    $stmt = $pdo->prepare('SELECT * FROM orders WHERE id = ?');
    $stmt->execute([$orderId]);
    $ordersResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sql = 'SELECT * FROM products INNER JOIN order_product 
            ON products.id = order_product.product_id 
            WHERE order_product.order_id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$orderId]);
    $productsResult = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    header('Location: orders.php');
    exit();
}

?>

<?php require_once 'includes/header.php'; ?>

<h1 style="text-align: center"><?= trans('Order'); ?></h1>

<div style="display: grid; justify-content: center;">
    <?php foreach ($ordersResult as $order) : ?>
        <table style="text-align: center; margin-bottom: 30px; border: 1px solid black;">
            <tr>
                <th><?= trans('Customer Name:'); ?></th>
                <td><?= $order['customer_name']; ?></td>
            </tr>

            <tr>
                <th><?= trans('Customer Details:'); ?></th>
                <td><?= $order['customer_details']; ?></td>
            </tr>

            <tr>
                <th><?= trans('Customer Comments:'); ?></th>
                <td><?= $order['customer_comments']; ?></td>
            </tr>

            <tr>
                <th><?= trans('Order Date:'); ?></th>
                <td><?= $order['creation_date']; ?></td>
            </tr>

            <tr>
                <th rowspan="<?= count($productsResult) + 1; ?>"><?= trans('Products:'); ?></th>
                <th><?= trans('Title'); ?></th>
                <th><?= trans('Description'); ?></th>
                <th><?= trans('Price'); ?></th>
                <th><?= trans('Image'); ?></th>
            </tr>

            <?php foreach ($productsResult as $product) : ?>
                <tr>
                    <td><?= $product['title']; ?></td>
                    <td><?= $product['description']; ?></td>
                    <td><?= $product['price']; ?></td>
                    <td><img src="<?= getImagePath($product); ?>" width="100" height="100"></td>
                </tr>
            <?php endforeach; ?>

            <tr>
                <th><?= trans('Total Price:'); ?></th>
                <td><?= $order['product_price_sum']; ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>
