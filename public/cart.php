<?php

    session_start();

    require_once "common.php";

    $pdo = pdoConnectMysql();

    //Add To Cart
    if (isset($_POST["product_id"]) && is_numeric($_POST["product_id"])) {
        $product_id = (int)$_POST["product_id"];

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$product_id]);

        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        // Check if the product exists (array is not empty)
        if (count($product) > 0) {
            // Product exists in database, now we can create/update the session variable for the cart
            if (isset($_SESSION["cart"]) && is_array($_SESSION["cart"])) {
                // Product is not in cart so add it
                $_SESSION["cart"][] = $product_id;
            } else {
                // There are no products in cart, this will add the first product to cart
                $_SESSION["cart"] = [$product_id];
            }
        }

        header("location: index.php");
        exit();
    }

    // Remove products from cart
    if (isset($_POST["product_id_to_remove"])) {
        if (is_numeric($_POST["product_id_to_remove"]) && count($_SESSION["cart"]) > 0) {
            // Remove the product from the shopping cart
            foreach ($_SESSION["cart"] as $index => $productInCartId) {
                if ((int) $productInCartId === (int) $_POST["product_id_to_remove"]) {
                    unset($_SESSION["cart"][$index]);

                    header("Location: cart.view.php");
                    exit();
                }
            }
        }
    }





