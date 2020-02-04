<?php

    session_start();

    require_once "common.php";

    if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"]) {
        header("Location: index.php");
        exit();
    }

?>

<form action="/login-check.php" method="POST">
    <h2> Login </h2>

    <input type="text" name="user" value="" placeholder="Enter name">

    <input type="password" name="password" value="" placeholder="Enter password">

    <button type="submit">Login</button>
</form>
