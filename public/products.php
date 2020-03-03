<?php

require_once 'common.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$pdo = pdoConnectMysql();

//Delete products
if (isset($_POST['product_id_to_remove']) && $_POST['product_id_to_remove']) {
    $id = (int) $_POST['product_id_to_remove'];

    $stmt = $pdo->prepare('DELETE FROM products WHERE id = ?');
    $stmt->execute([$id]);

    header('Location: products.php');
    exit();
}

//List all products
$stmt = $pdo->prepare('SELECT * FROM products');
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php require_once 'includes/header.php'; ?>

<div style="width: 600px;">
    <h2><?= trans('All products'); ?></h2>

    <?php foreach ($products as $product) : ?>
        <div style="border: 1px solid black; margin: 10px; display: flex; align-items: center;
             justify-content: space-evenly;">

            <img src="<?= getImagePath($product); ?>" alt="<?= trans('product_image'); ?>"
                 style="width: 100px; height: 100px">

            <div>
                <span><b><?= trans('Title:'); ?></b></span>
                <span><?= $product['title']; ?></span> <br>

                <span><b><?= trans('Description:'); ?></b></span>
                <span><?= $product['description']; ?></span> <br>

                <span><b><?= trans('Price:'); ?></b></span>
                <span><?= $product['price']; ?></span> <br>
            </div>

            <a href="/product.php?id=<?= $product['id']; ?>"><?= trans('Edit'); ?></a>

            <div>
                <form action="products.php" method="POST">
                    <input type="hidden" name="product_id_to_remove" value="<?= $product['id']; ?>">

                    <button type="submit"><?= trans('Remove'); ?></button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div style="margin: 20px; width: 500px; display: flex; justify-content: space-evenly;">
    <a href="product.php"><?= trans('Add'); ?></a>

    <a href="logout.php"><?= trans('Logout'); ?></a>
</div>

<?php require_once 'includes/footer.php'; ?>

