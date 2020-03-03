<?php

require_once 'common.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$pdo = pdoConnectMysql();

$stmt = $pdo->prepare('SELECT * FROM orders');
$stmt->execute();
$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require_once 'includes/header.php'; ?>

<h1 style="text-align: center"><?= trans('Orders'); ?></h1>

<div style="display: grid; justify-content: center;">
    <?php foreach ($orders as $order) : ?>
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
                <th><?= trans('Total Price:'); ?></th>
                <td><?= $order['product_price_sum']; ?></td>
            </tr>
        </table>
    <?php endforeach; ?>
</div>

<?php require_once 'includes/footer.php'; ?>

