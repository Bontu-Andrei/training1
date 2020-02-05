<?php
require_once "common.php";

$session = session();

$pdo = pdoConnectMysql();

$productsFromCart = getAllProductsFromCart();

$idsProductsNotInCart = [];

foreach ($productsFromCart as $productFromCart) {
    $idsProductsNotInCart = (int)$productFromCart["id"];
}

$stmt = $pdo->prepare("SELECT * FROM products WHERE id NOT IN ($idsProductsNotInCart)");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!array_key_exists("cart", $_SESSION)) {
    $_SESSION["cart"] = [];
}

$productsNotInCart = [];

if (count($_SESSION["cart"]) !== 0) {
    foreach ($products as $index => $productInDB) {
        $existsInCart = false;

        foreach ($_SESSION["cart"] as $productId) {
            if ((int)$productId === (int)$productInDB["id"]) {
                $existsInCart = true;
            }
        }

        if (!$existsInCart) {
            $productsNotInCart[] = $productInDB;
        }
    }
} else {
    $productsNotInCart = $products;
}
?>

<?php include "includes/header.php"; ?>

<h2><?= trans("Products who does not exist in the cart") ?></h2>

<?php foreach ($productsNotInCart as $productNotInCart) : ?>
    <div style="border: 1px solid black; width: 600px; height: 120px; margin: 10px; display: flex;
                                 align-items: center; justify-content: space-evenly;">

        <img src="<?= getImagePath($productNotInCart) ?>" alt="<?= trans("product_image") ?>"
             style="width: 100px; height: 100px;">

        <div>
            <label for="title"><b><?= trans("Title:") ?></b></label>
            <span name="title"><?= $productNotInCart["title"] ?></span> <br>

            <label for="description"><b><?= trans("Description:") ?></b></label>
            <span name="description"><?= $productNotInCart["description"] ?></span> <br>

            <label for="price"><b><?= trans("Price:") ?></b></label>
            <span name="price"><?= $productNotInCart["price"] ?></span> <br>
        </div>

        <form action="cart.php" method="POST">
            <input type="hidden" value="<?= $productNotInCart["id"] ?>" name="product_id">
            <button type="submit">Add to cart</button>
        </form>
    </div>
<?php endforeach; ?>

<?php include "includes/footer.php"; ?>


