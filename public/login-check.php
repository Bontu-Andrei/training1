<?php

require_once 'common.php';

if (isset($_POST['user']) && isset($_POST['password'])) {
    if (strip_tags($_POST['user']) === ADMIN_USERNAME && strip_tags($_POST['password']) === ADMIN_PASSWORD) {
        // logged in
        $_SESSION['logged_in'] = true;

        header('Location: products.php');
        exit();
    }
}

header('Location: login.php');
exit();
