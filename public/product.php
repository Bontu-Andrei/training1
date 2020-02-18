<?php

require_once 'common.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$pdo = pdoConnectMysql();

// Handle POST request
if (count($_POST) > 0) {
    $id = isset($_POST['id']) ? strip_tags($_POST['id']) : '';

    if ($id) {
        // Edit request
        $action = 'edit';

        $editedProduct = getProductById($id);

        if (!$editedProduct) {
            exit('Something went wrong.');
        }
    } else {
        // Create request
        $action = 'create';
        $editedProduct = null;
    }

    $errors = [];

    // Validation.
    if (!validateRequiredInput('title')) {
        $errors['title'] = 'Title field is required.';
    }

    if (!validateRequiredInput('description')) {
        $errors['description'] = 'Description field is required.';
    }

    if (!validateRequiredInput('price')) {
        $errors['price'] = 'Price field is required.';
    }

    if (!validateRequiredFileInput('image_file') && $action === 'create') {
        $errors['image_file'] = 'The image is required.';
    }

    if (!$errors) {
        if ($_FILES['image_file']['name'] != '') {
            $file = $_FILES['image_file'];
            $fileName = $file['name'];
            $fileTmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileError = $file['error'];
            $fileType = $file['type'];

            $fileExt = explode('.', $fileName);
            $fileActualExt = strtolower(end($fileExt));

            $allowed = ['jpg', 'jpeg', 'png'];

            if (!in_array($fileActualExt, $allowed)) {
                exit('You cannot upload files of this type!');
            }

            if ($fileError !== 0) {
                exit('There was an error uploading your file!');
            }

            if ($fileSize > 1000000) {
                exit('Your file is too big!');
            }

            $fileNameNew = uniqid('', true).'.'.$fileActualExt;

            $fileDestination = 'images/'.$fileNameNew;

            move_uploaded_file($fileTmpName, $fileDestination);

            $image_path = strip_tags($fileNameNew);
        } elseif ($action === 'edit') {
            $image_path = $editedProduct['image_path'];
        }

        $data = [
            strip_tags($_POST['title']),
            strip_tags($_POST['description']),
            strip_tags($_POST['price']),
            $image_path,
        ];

        if ($action === 'create') {
            $sql = 'INSERT INTO products (title, description, price, image_path) VALUES (?, ?, ?, ?)';
        } else {
            $sql = 'UPDATE products SET title = ?, description = ?, price = ?, image_path = ? WHERE id = ?';
        }

        $stmt = $pdo->prepare($sql);

        if ($action === 'edit') {
            $data[] = (int) $id;
        }

        $stmt->execute($data);

        header('Location: products.php');
        exit();
    }
} else {
    // Handle GET request
    if (isset($_GET['id']) && !empty($_GET) && $_GET['id']) {
        // EDIT product
        $result = getProductById($_GET['id']);

        if (!$result) {
            exit('No product exists in our DB.');
        }

        $action = 'edit';
        $editedProduct = $result;
    } else {
        // CREATE product
        $action = 'create';
    }
}

?>

<?php require_once 'includes/header.php'; ?>

<?php require_once 'includes/product-form.php'; ?>

<?php require_once 'includes/footer.php'; ?>
