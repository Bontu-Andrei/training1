<?php
$h1 = trans('Training');
$index = trans('Index');
$cart = trans('Cart');
$products = trans('Products');
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
            <a href="index.php">$index</a>
            <a href="cart.php">$cart</a>
            <a href="products.php">$products</a>
        </nav>
    </div>
</header>
<main>
EOT;
?>

