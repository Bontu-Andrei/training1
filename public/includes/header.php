<?php
$h1 = trans('Training');
$namePages = [
    'index' => trans('Index'),
    'cart' => trans('Cart'),
    'products' => trans('Products')
];
?>
<?=<<<EOT
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title></title>
</head>
<body>
<header>
    <div style="text-align: center">
        <h1>$h1</h1>
        <nav>
            <a href="index.php">Index</a>
            <a href="cart.php">Cart</a>
            <a href="products.php">Products</a>
        </nav>
    </div>
</header>
<main>
EOT;
?>

