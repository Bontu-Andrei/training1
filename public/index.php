<?php

require_once 'common.php';

$pdo = pdoConnectMysql();

$idsProductsInCart = getAllProductsFromCartIds();

if (count($idsProductsInCart) > 0) {
    $in = implode(',', array_fill(0, count($idsProductsInCart), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id NOT IN ($in)");
    $stmt->execute($idsProductsInCart);
    $productsNotInCart = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    //Fetch all products because we don't have any products in cart
    $stmt = $pdo->prepare('SELECT * FROM products');
    $stmt->execute();
    $productsNotInCart = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>

<?php require_once 'includes/header.php' ?>

<h2><?= trans('Products who does not exist in the cart') ?></h2>

<?php foreach ($productsNotInCart as $productNotInCart) : ?>
    <div style="border: 1px solid black; width: 600px; height: 120px; margin: 10px; display: flex;
                                 align-items: center; justify-content: space-evenly;">

        <img src="<?= getImagePath($productNotInCart) ?>" alt="<?= trans('product_image') ?>"
             style="width: 100px; height: 100px;">

        <div>
            <span><b><?= trans('Title:') ?></b></span>
            <span><?= $productNotInCart['title'] ?></span> <br>

            <span><b><?= trans('Description:') ?></b></span>
            <span><?= $productNotInCart['description'] ?></span> <br>

            <span><b><?= trans('Price:') ?></b></span>
            <span><?= $productNotInCart['price'] ?></span> <br>
        </div>

        <form action="cart.php" method="POST">
            <input type="hidden" value="<?= $productNotInCart['id'] ?>" name="product_id">
            <button type="submit"><?= trans('Add to cart') ?></button>
        </form>
    </div>
<?php endforeach; ?>

<?php require_once 'includes/footer.php' ?>


