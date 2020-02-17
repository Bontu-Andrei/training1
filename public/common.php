<?php

session_start();

require_once 'config.php';

function pdoConnectMysql()
{
    try {
        return new PDO('mysql:host='.DB_SERVER.';dbname='.DB_NAME.';charset=utf8', DB_USER, DB_PASSWORD);
    } catch (PDOException $exception) {
        die('Failed to connect to database!');
    }
}

function trans($name)
{
    return $name;
}

function getImagePath($product)
{
    if ($product['image_path']) {
        return '/images/'.$product['image_path'];
    } else {
        return '/images/default.jpg';
    }
}

function validateRequiredInput($name)
{
    return isset($_POST[$name]) && $_POST[$name];
}

function validateRequiredFileInput($name)
{
    return $_FILES[$name]['size'] !== 0 && $_FILES[$name]['error'] === 0;
}

function getImageEncoding($product)
{
    $path = getImagePath($product);

    $image = file_get_contents(__DIR__.$path);

    if ($image !== false) {
        return 'data:image/jpg;base64,'.base64_encode($image);
    }

    return '';
}

function getProductById($productId)
{
    $pdo = pdoConnectMysql();

    // Fetch the product from the DB.
    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$productId]);
    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$result || count($result) === 0) {
        return null;
    }

    return $result[0];
}
