<?php

require_once 'common.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$pdo = pdoConnectMysql();

$sql = 'SELECT o.id, o.customer_name, o.creation_date, o.customer_details, o.customer_comments, o.product_price_sum, 
               p.title, p.description, p.price, p.image_path
        FROM orders AS o 
        INNER JOIN order_product AS op ON o.id = op.order_id 
        INNER JOIN products as p ON p.id = op.product_id';

$stmt = $pdo->prepare($sql);
$stmt->execute();
$ordersResult = $stmt->fetchAll(PDO::FETCH_ASSOC);

$orders = []; // The key is the id of the order.

foreach ($ordersResult as $orderItem) {
    if (!array_key_exists($orderItem['id'], $orders)) {
        $orders[$orderItem['id']] = [
            'customer_name' => $orderItem['customer_name'],
            'customer_details' => $orderItem['customer_details'],
            'customer_comments' => $orderItem['customer_comments'],
            'creation_date' => $orderItem['creation_date'],
            'product_price_sum' => $orderItem['product_price_sum'],
            'products' => [],
        ];
    }

    $orders[$orderItem['id']]['products'][] = [
        'title' => $orderItem['title'],
        'description' => $orderItem['description'],
        'price' => $orderItem['price'],
        'image_path' => $orderItem['image_path'],
    ];
}

?>

<?php require_once 'includes/header.php'; ?>

<h1 style="text-align: center"><?= trans('Orders'); ?></h1>

<div style="display: grid; justify-content: center;">
    <?php foreach ($orders as $orderId => $order) : ?>
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
                <th rowspan="<?= count($order['products']) + 1; ?>"><?= trans('Products:'); ?></th>
                <th><?= trans('Title'); ?></th>
                <th><?= trans('Description'); ?></th>
                <th><?= trans('Price'); ?></th>
                <th><?= trans('Image'); ?></th>
            </tr>

            <?php foreach ($order['products'] as $product) : ?>
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

