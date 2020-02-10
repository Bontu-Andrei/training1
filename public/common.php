<?php

session_start();

require_once 'config.php';

function pdoConnectMysql() {
    try {
        return new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
    } catch (PDOException $exception) {
        die ('Failed to connect to database!');
    }
}

function trans($name) {
    return $name;
}

function getAllProductsFromCart() {
    $pdo = pdoConnectMysql();

    //List products from cart
    $productsInCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    $products = [];

    if (count($productsInCart) > 0) {
        $arrayToQuestionMarks = implode(',', array_fill(0, count($productsInCart), '?'));

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($arrayToQuestionMarks)");
        // We only need the array keys, not the values, the keys are the id's of the products
        $stmt->execute(array_values($productsInCart));
        // Fetch the products from the database and return the result as an Array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $products;
}

function getImagePath($product) {
    if ($product['image_path']) {
        return '/images/' . $product['image_path'];
    } else {
        return '/images/default.jpg'; }
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

    $allowed = array('jpg', 'jpeg', 'png');

    if ( ! in_array($fileActualExt, $allowed)) {
        return [
            'success' => false,
            'error' => 'You cannot upload files of this type!'
        ];
    }

    if ($fileError !== 0) {
        return [
            'success' => false,
            'error' => 'There was an error uploading your file!'
        ];
    }

    if ($fileSize > 1000000) {
        return [
            'success' => false,
            'error' => 'Your file is too big!'
        ];
    }

    $fileNameNew = uniqid('', true).'.'.$fileActualExt;

    $fileDestination = 'images/'.$fileNameNew;

    move_uploaded_file($fileTmpName, $fileDestination);

    return [
        'success' => true,
        'filename' => $fileNameNew
    ];
}

function validateRequiredInput($name) {
    return isset($_POST[$name]) && $_POST[$name];
}

function validateRequiredFileInput($name) {
    return $_FILES[$name]['size'] !== 0 && $_FILES[$name]['error'] === 0;
}

function getImageEncoding($product) {
    $path = getImagePath($product);

    $image = file_get_contents(__DIR__ . $path);

    if ($image !== false){
        return 'data:image/jpg;base64,' . base64_encode($image);
    }

    return '';
}
