<?php

require_once "common.php";

$pdo = pdoConnectMysql();

if (isset($_POST["product_id_to_remove"])) {

    $id = (int) $_POST["product_id_to_remove"];

    $sql = "DELETE FROM products WHERE id = $id";

    $pdo->exec($sql);

    $pdo = null;

    header("Location: products.php");
    exit();
}
