<?php

require_once 'common.php';

$products = getAllProductsFromCart();

$checkoutDate = date('Y-m-d H:i:s');

$data = [
    'customer_name' => strip_tags($_POST['customer_name']),
    'customer_details' => strip_tags($_POST['customer_details']),
    'customer_comments' => strip_tags($_POST['customer_comments']),
    'creation_date' => $checkoutDate,
];

?>

<html>
<head>
    <title><?= trans('Checkout'); ?></title>
</head>
<body>

<h1><?= trans('Thanks for your order, ') . $data['customer_name']; ?></h1>

<?php foreach ($products as $product) : ?>
    <table cellspacing="0" style="border: 2px dashed #FB4314; width: 50%;">
        <tr>
            <img src="<?= getImageEncoding($product); ?>" alt="<?= trans('product_image'); ?>"
                 style="width: 100px; height: 100px;">
        </tr>

        <tr>
            <th><?= trans('Title'); ?></th>
            <td><?= $product['title']; ?></td>
        </tr>

        <tr style="background-color: #e0e0e0;">
            <th><?= trans('Description'); ?></th>
            <td><?= $product['description']; ?></td>
        </tr>

        <tr>
            <th><?= trans('Price'); ?></th>
            <td><?= $product['price']; ?></td>
        </tr>
    </table>
<?php endforeach; ?>

<h3><?= trans('Contact details: ') . $data['customer_details']; ?></h3>
<h3><?= trans('Comments: ') . $data['customer_comments']; ?></h3>
<h3><?= trans('Created at: ') . $data['creation_date']; ?></h3>

</body>
</html>