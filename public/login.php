<?php

require_once 'common.php';

if (isset($_POST['user']) && isset($_POST['password'])) {
    $errors = [];

    if (!validateRequiredInput('user')) {
        $errors['user'] = 'User field is required.';
    }

    if (!validateRequiredInput('password')) {
        $errors['password'] = 'Password field is required.';
    }

    if (!$errors && strip_tags($_POST['user']) === ADMIN_USERNAME && strip_tags($_POST['password']) === ADMIN_PASSWORD) {
        // logged in
        $_SESSION['logged_in'] = true;

        header('Location: products.php');
        exit();
    }
}

if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) {
    header('Location: index.php');
    exit();
}

?>

<form action="login.php" method="POST" style="display: grid; justify-content: center;">
    <h3><?= trans('Login'); ?></h3>

    <input type="text" name="user" value="<?= isset($_POST['user']) ? $_POST['user'] : ''; ?>"
           placeholder="<?= trans('Enter name'); ?>">

    <?php if (isset($errors['user'])) : ?>
        <div style="color: red;">
            <?= $errors['user']; ?>
        </div>
    <?php endif; ?>

    <br>

    <input type="password" name="password" value="<?= isset($_POST['password']) ? $_POST['password'] : ''; ?>"
           placeholder="<?= trans('Enter password'); ?>">

    <?php if (isset($errors['password'])) : ?>
        <div style="color: red;">
            <?= $errors['password']; ?>
        </div>
    <?php endif; ?>

    <br>
    
    <button type="submit"><?= trans('Login'); ?></button>
</form>
