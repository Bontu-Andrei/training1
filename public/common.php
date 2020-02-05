<?php

require_once "config.php";

function pdoConnectMysql() {
    try {
        return new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME . ";charset=utf8", DB_USER, DB_PASSWORD);
    } catch (PDOException $exception) {
        die ("Failed to connect to database!");
    }
}

function session() {
    return session_start();
}

function trans($name) {
    return $name;
}

function getAllProductsFromCart() {
    $pdo = pdoConnectMysql();

    //List products from cart
    $productsInCart = isset($_SESSION["cart"]) ? $_SESSION["cart"] : array();
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

    return $products;
}

function getImagePath($product) {
    if ($product["image_path"]) {
        return "/images/" . $product["image_path"];
    } else {
        return "/images/default.jpg"; }
}

