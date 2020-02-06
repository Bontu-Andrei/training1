<?php

require_once "common.php";

$session = session();

if (!$_SESSION["logged_in"]) {
    // not auth so we redirect.
    header("Location: login.php");
    exit();
}

$pdo = pdoConnectMysql();

$stmt = $pdo->prepare("SELECT * FROM products");
$stmt->execute();
$products = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<?php include "includes/header.php"; ?>

<div style="width: 600px;">
    <h2><?= trans("All products") ?></h2>

    <?php foreach ($products as $product) : ?>
        <div style="border: 1px solid black; margin: 10px; display: flex; align-items: center;
             justify-content: space-evenly;">

            <img src="<?= getImagePath($product) ?>" alt="<?= trans("product_image") ?>"
                 style="width: 100px; height: 100px">

            <div>
                <span><b><?= trans("Title:") ?></b></span>
                <span><?= $product["title"] ?></span> <br>

                <span><b><?= trans("Description:") ?></b></span>
                <span><?= $product["description"] ?></span> <br>

                <span><b><?= trans("Price:") ?></b></span>
                <span><?= $product["price"] ?></span> <br>
            </div>

            <a href="edit.php"><?= trans("Edit") ?></a>

            <div>
                <form action="delete.php" method="POST">
                    <input type="hidden" name="product_id_to_remove" value="<?= $product['id'] ?>">

                    <button type="submit"><?= trans("Remove") ?></button>
                </form>
            </div>
        </div>
    <?php endforeach; ?>
</div>

<div style="margin: 20px; width: 500px; display: flex; justify-content: space-evenly;">
    <a href="product.php"><?= trans("Add") ?></a>

    <a href="logout.php"><?= trans("Logout") ?></a>
</div>

<?php include "includes/footer.php"; ?>

