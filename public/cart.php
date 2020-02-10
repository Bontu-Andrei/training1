<?php

require_once 'common.php';

$pdo = pdoConnectMysql();

$products = getAllProductsFromCart();

//Add To Cart
if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $product_id = (int) $_POST['product_id'];

    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$product_id]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if the product exists (array is not empty)
    if (count($product) > 0) {
        // Product exists in database, now we can create/update the session variable for the cart
        if (isset($_SESSION['cart']) && is_array($_SESSION['cart'])) {
            // Product is not in cart so add it
            $_SESSION['cart'][] = $product_id;
        } else {
            // There are no products in cart, this will add the first product to cart
            $_SESSION['cart'] = [$product_id];
        }
    }

    header('Location: index.php');
    exit();
}

// Remove products from cart
if (isset($_POST['product_id_to_remove'])) {
    if (is_numeric($_POST['product_id_to_remove']) && count($_SESSION['cart']) > 0) {
        foreach ($_SESSION['cart'] as $index => $productInCartId) {
            if ((int) $productInCartId === (int) $_POST['product_id_to_remove']) {
                unset($_SESSION['cart'][$index]);

                header('Location: cart.php');
                exit();
            }
        }
    }
}

?>

<?php require_once 'includes/header.php'; ?>

<div style="border: 1px solid black; width: 600px; height: auto;">
    <h3 style="text-align: center"><?= trans('Cart'); ?></h3>

    <?php foreach ($products as $product) : ?>
        <div style="border: 1px solid black; margin: 10px; display: flex; align-items: center;
                                         justify-content: space-evenly;">

            <img src="<?= getImagePath($product); ?>" alt="<?= trans('product_image'); ?>"
                 style="width: 100px; height: 100px;">

            <div>
                <span><b><?= trans('Title:'); ?></b></span>
                <span><?= $product['title']; ?></span> <br>

                <span><b><?= trans('Description:'); ?></b></span>
                <span><?= $product['description']; ?></span> <br>

                <span><b><?= trans('Price:'); ?></b></span>
                <span><?= $product['price']; ?></span> <br>
            </div>

            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id_to_remove" value="<?= $product['id']; ?>">

                <button type="submit"><?= trans('Remove'); ?></button>
            </form>
        </div>
    <?php endforeach; ?>

    <div style="margin: 30px;">
        <form action="checkout.php" method="POST">
            <div style="display: grid">
                <textarea name="customer_name" cols="50" rows="2" placeholder="<?= trans('Name'); ?>"
                          required></textarea> <br>

                <textarea name="customer_details" cols="50" rows="3" placeholder="<?= trans('Contact details'); ?>"
                          required></textarea>
                <br>

                <textarea name="customer_comments" cols="50" rows="4"
                          placeholder="<?= trans('Comments'); ?>"></textarea> <br>
            </div>

            <div style="float: right;">
                <a href="index.php"><?= trans('Go to index'); ?></a>

                <button type="submit" name="checkout"><?= trans('Checkout'); ?></button>
            </div>
        </form>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>





