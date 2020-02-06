<?php

require_once "common.php";

$session = session();

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
    header("Location: index.php");
    exit();
}

?>

<form action="/login-check.php" method="POST">
    <h2><?= trans("Login") ?></h2>

    <input type="text" name="user" value="" placeholder="<?= trans("Enter name")?>">

    <input type="password" name="password" value="" placeholder="<?= trans("Enter password") ?>">

    <button type="submit"><?= trans("Login") ?></button>
</form>
