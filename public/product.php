<?php

require_once 'common.php';

if (!$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$pdo = pdoConnectMysql();

//Add a new post
if (count($_POST) > 0) {
    $_SESSION['errors'] = [];

    // Validation.
    if (!validateRequiredInput('title')) {
        $_SESSION['errors']['title'] = 'The title is required.';
    }

    if (!validateRequiredInput('description')) {
        $_SESSION['errors']['description'] = 'The description is required.';
    }

    if (!validateRequiredInput('price')) {
        $_SESSION['errors']['price'] = 'The price is required.';
    }

    if (!validateRequiredFileInput('image_file')) {
        $_SESSION['errors']['image_file'] = 'The image is required.';
    }

    if (count($_SESSION['errors']) === 0) {
        $_SESSION['errors'] = [];

        $response = uploadImage();

        if (!$response['success']) {
            $_SESSION['errors']['error'] = $response['error'];
        } else {
            $data = [
                'title' => strip_tags($_POST['title']),
                'description' => strip_tags($_POST['description']),
                'price' => strip_tags($_POST['price']),
                'image_path' => strip_tags($response['filename']),
            ];

            $sql = 'INSERT INTO products (title, description, price, image_path) VALUES (?, ?, ?, ?)';

            $stmt = $pdo->prepare($sql);

            $stmt->execute([
                $data['title'],
                $data['description'],
                $data['price'],
                $data['image_path'],
            ]);

            header('Location: products.php');
            exit();
        }
    }
}

$action = 'create';
?>

<?php require_once 'includes/header.php'; ?>

<?php require_once 'includes/product-form.php'; ?>

<?php require_once 'includes/footer.php'; ?>
