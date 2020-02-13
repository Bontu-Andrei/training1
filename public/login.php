<?php

require_once 'common.php';

if (isset($_POST['user']) && isset($_POST['password'])) {
    $errors = [
        'user' => '',
        'password' => '',
    ];

    if (!validateRequiredInput('user')) {
        $errors['user'] = 'User field is required.';
    }

    if (!validateRequiredInput('password')) {
        $errors['password'] = 'Password field is required.';
    }

    if (!$errors['user'] && !$errors['password']) {
        $errors = [
            'user' => '',
            'password' => '',
        ];

        if (strip_tags($_POST['user']) === ADMIN_USERNAME && strip_tags($_POST['password']) === ADMIN_PASSWORD) {
            // logged in
            $_SESSION['logged_in'] = true;

            header('Location: products.php');
            exit();
        }
    }
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header('Location: index.php');
    exit();
}

?>

<form action="<?= $_SERVER['PHP_SELF']; ?>" method="POST" style="display: grid; justify-content: center;">
    <h3><?= trans('Login'); ?></h3>

    <input type="text" name="user" value="<?= isset($_POST['user']) ? $_POST['user'] : ''; ?>"
           placeholder="<?= trans('Enter name'); ?>">

    <div style="color: red;">
        <?= isset($errors['user']) ? $errors['user'] : ''; ?>
    </div>

    <br>

    <input type="password" name="password" value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>"
           placeholder="<?= trans('Enter password'); ?>">

    <div style="color: red;">
        <?= isset($errors['password']) ? $errors['password'] : ''; ?>
    </div>

    <br>
    <button type="submit"><?= trans('Login'); ?></button>
</form>
