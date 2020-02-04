<?php

    session_start();

    require_once "common.php";

    $products = getAllProductsFromCart();

?>

<?=template_header("Cart")?>

    <div style="border: 1px solid black; width: 600px; height: auto;">
        <h3 style="text-align: center">Cart</h3>

        <?php foreach ($products as $product) : ?>
            <div style="border: 1px solid black; margin: 10px; display: flex; align-items: center;
                                         justify-content: space-evenly;">

                <img src="images/default.jpg" alt="product_image" style="width: 100px; height: 100px;">

                <div>
                    <label for="title"><b><?= trans("Title:") ?></b></label>
                    <span name="title"><?= $product["title"] ?></span> <br>

                    <label for="description"><b><?= trans("Description:") ?></b></label>
                    <span name="description"><?= $product["description"] ?></span> <br>

                    <label for="price"><b><?= trans("Price:") ?></b></label>
                    <span name="price"><?= $product["price"] ?></span> <br>
                </div>

                <form action="cart.php" method="POST">
                    <input type="hidden" name="product_id_to_remove" value="<?= $product["id"] ?>">

                    <button type="submit">Remove</button>
                </form>
            </div>
        <?php endforeach; ?>

        <div style="margin: 30px;">
            <form action="checkout.php" method="POST">
                <textarea name="customer_name" cols="74" rows="2" placeholder="Name" required></textarea>

                <textarea name="customer_details" cols="74" rows="3" placeholder="Contact details" required></textarea>

                <textarea name="comments" cols="74" rows="4" placeholder="Comments"></textarea> <br>

               <div style="float: right;">
                   <a href="index.php">Go to index</a>

                   <button type="submit" name="checkout">Checkout</button>
               </div>
            </form>
        </div>

    </div>

<?=template_footer()?>