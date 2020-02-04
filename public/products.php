<?php

    session_start();

    require_once "common.php";

    if ( ! $_SESSION["logged_in"]) {
        // not auth so we redirect.
        header("Location: login.php");
        exit();
    }

    $pdo = pdoConnectMysql();

    $stmt = $pdo->prepare("SELECT * FROM products LIMIT 500");
    $stmt->execute();
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<?=template_header("Products")?>

    <div style="width: 600px;">
        <h2>All products</h2>

        <?php foreach ($products as $product) : ?>
            <div style="border: 1px solid black; margin: 10px; display: flex; align-items: center;
                                                     justify-content: space-evenly;">

                <img src="images/default.jpg" alt="product_image" style="width: 100px; height: 100px">

                <div>
                    <label for="title"><b><?= trans("Title:") ?></b></label>
                    <span name="title"><?= $product["title"] ?></span> <br>

                    <label for="description"><b><?= trans("Description:") ?></b></label>
                    <span name="description"><?= $product["description"] ?></span> <br>

                    <label for="price"><b><?= trans("Price:") ?></b></label>
                    <span name="price"><?= $product["price"] ?></span> <br>
                </div>

                <a href="edit.php">Edit</a>

                <div>
                    <form action="delete.php" method="POST">
                        <input type="hidden" name="product_id_to_remove" value="<?= $product['id'] ?>">

                        <button type="submit">Remove</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div style="margin: 20px; width: 500px; display: flex; justify-content: space-evenly;">
        <a href="product.php">Add</a>

        <a href="logout.php">Logout</a>
    </div>

<?=template_footer("")?>

