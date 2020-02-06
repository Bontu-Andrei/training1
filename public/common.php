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

function getAbsoluteImageUrl($product) {
    return serverAbsolutePath().getImagePath($product);
}

function serverAbsolutePath() {
    $serverName = $_SERVER["SERVER_NAME"];

    if (!in_array($_SERVER["SERVER_PORT"], [80, 443])) {
        $port = ":".$_SERVER["SERVER_PORT"];
    } else {
        $port = "";
    }

    if (!empty($_SERVER["HTTPS"]) && (strtolower($_SERVER["HTTPS"]) == "on" || $_SERVER["HTTPS"] == "1")) {
        $scheme = "https";
    } else {
        $scheme = "http";
    }
    return $scheme."://".$serverName.$port;
}

function uploadImage() {
    $file = $_FILES['image_file'];
    $fileName = $file['name'];
    $fileTmpName = $file['tmp_name'];
    $fileSize = $file['size'];
    $fileError = $file['error'];
    $fileType = $file['type'];

    $fileExt = explode('.', $fileName);
    $fileActualExt = strtolower(end($fileExt));

    $allowed = array('jpg', 'jpeg', 'png', 'pdf');

    if (in_array($fileActualExt, $allowed)) {
        if ($fileError === 0) {
            if ($fileSize < 1000000) {
                $fileNameNew = uniqid('', true).".".$fileActualExt;

                $fileDestination = 'images/'.$fileNameNew;

                move_uploaded_file($fileTmpName, $fileDestination);

                return [
                    'success' => true,
                    'filename' => $fileNameNew
                ];
            } else {
                return [
                    'success' => false,
                    'error' => 'Your file is too big!'
                ];
            }
        } else {
            return [
                'success' => false,
                'error' => 'There was an error uploading your file!'
            ];
        }
    } else {
        return [
            'success' => false,
            'error' => 'You cannot upload files of this type!'
        ];
    }
}

