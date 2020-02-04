<?php

    session_start();

    require_once "common.php";

    $pdo = pdo_connect_mysql();

    //Add To Cart
    if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
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

    //List products from cart
    $productsInCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    $products = [];

    if (count($productsInCart) > 0) {
        // Products in cart array to question mark string array, we need the SQL statement to include IN (?,?,?,...etc)
        $arrayToQuestionMarks = implode(",", array_fill(0, count($productsInCart), "?"));

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN (" . $arrayToQuestionMarks . ")");
        // We only need the array keys, not the values, the keys are the id's of the products
        $stmt->execute(array_values($productsInCart));
        // Fetch the products from the database and return the result as an Array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }



