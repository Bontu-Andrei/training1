<?php

require_once "config.php";
require_once "common.php";

$session = session();

if (isset($_POST["user"]) && isset($_POST["password"])) {
    if ($_POST["user"] === ADMIN_USERNAME && $_POST["password"] === ADMIN_PASSWORD) {
        // logged in
        $_SESSION["logged_in"] = true;

        header("Location: products.php");
        exit();
    }
}

header("Location: login.php");
exit();
