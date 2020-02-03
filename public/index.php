<?php

    session_start();

    require_once 'common.php';

    $pdo = pdo_connect_mysql();

    $stmt = $pdo->prepare('SELECT * FROM products LIMIT 500');
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ( ! array_key_exists('cart', $_SESSION)) {
        $_SESSION['cart'] = [];
    }

    $productsNotInCart = [];

    if (count($_SESSION['cart']) !== 0) {
        foreach ($products as $index => $productInDB) {
            $existsInCart = false;

            foreach ($_SESSION['cart'] as $productId) {
                if ((int) $productId === (int) $productInDB['id']) {
                    $existsInCart = true;
                }
            }

            if ( ! $existsInCart) {
                $productsNotInCart[] = $productInDB;
            }
        }
    } else {
        $productsNotInCart = $products;
    }

?>


<?=template_header('Home')?>

    <h2>Products who does not exist in the cart</h2>

    <?php foreach ($productsNotInCart as $productNotInCart) : ?>
        <div style="border: 1px solid black; width: 600px; height: 120px; margin: 10px; display: flex;
                                 align-items: center; justify-content: space-evenly;">

            <img src="images/default.jpg" alt="product_image" style="width: 100px; height: 100px;">

            <div>
                <label for="title"><b><?= trans('Title:') ?></b></label>
                <span name="title"><?= $productNotInCart['title'] ?></span> <br>

                <label for="description"><b><?= trans('Description:') ?></b></label>
                <span name="description"><?= $productNotInCart['description'] ?></span> <br>

                <label for="price"><b><?= trans('Price:') ?></b></label>
                <span name="price"><?= $productNotInCart['price'] ?></span> <br>
            </div>

            <form action="cart.php" method="POST">
                <input type="hidden" value="<?= $productNotInCart['id'] ?>" name="product_id">
                <button type="submit">Add to cart</button>
            </form>
        </div>
    <?php endforeach; ?>

<?=template_footer('')?>
