<?php

require_once 'common.php';

if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
    header('Location: login.php');
    exit();
}

$pdo = pdoConnectMysql();

$formValues = [
    'title' => '',
    'description' => '',
    'price' => '',
];

// Handle POST request
if (isset($_POST['save'])) {
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

    if (count($_POST)) {
        $formValues = $_POST;
    } elseif ($action === 'edit') {
        $formValues = $editedProduct;
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

    if ($action === 'create' || file_exists($_FILES['image_file']['tmp_name'])) {
        if (!validateRequiredFileInput('image_file')) {
            $errors['image_file'] = 'The image is required.';
        }

        if ($_FILES['image_file']['size'] > 1000000) {
            $errors['image_size'] = 'Your image is too big.';
        }

        if ($_FILES['image_file']['tmp_name']) {
            $fileInfo = finfo_open(FILEINFO_MIME_TYPE);
            $detectedType = finfo_file($fileInfo, $_FILES['image_file']['tmp_name']);
            finfo_close($fileInfo);

            if (!in_array($detectedType, ['image/jpeg', 'image/png'])) {
                $errors['image_file'] = 'Please upload a valid image.';
            }
        }
    }

    if (!$errors) {
        if ($_FILES['image_file']['name'] != '') {
            $extension = mime_content_type($_FILES['image_file']['tmp_name']);

            if ($extension == 'image/jpeg' || $extension == 'image/png') {
                $imagePath = round(microtime(true)).'.'.substr(strrchr($extension, '/'), 1);

                move_uploaded_file($_FILES['image_file']['tmp_name'], 'images/'.$imagePath);
            }
        } elseif ($action === 'edit') {
            $imagePath = $editedProduct['image_path'];
        }

        $data = [
            strip_tags($_POST['title']),
            strip_tags($_POST['description']),
            strip_tags($_POST['price']),
            $imagePath,
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
    if (isset($_GET['id']) && $_GET['id']) {
        // EDIT product

        if (!getProductById($_GET['id'])) {
            exit('No product exists in our DB.');
        }

        $action = 'edit';
        $editedProduct = getProductById($_GET['id']);
        $formValues = $editedProduct;
    } else {
        // CREATE product
        $action = 'create';
    }
}

?>

<?php require_once 'includes/header.php'; ?>

    <div style="display: flex; justify-content: center; margin-top: 10px;">
        <form action="product.php" method="POST" enctype="multipart/form-data">
            <?php if ($action === 'edit') : ?>
                <input type="hidden" name="id" value="<?= $editedProduct['id']; ?>">
            <?php endif; ?>
            <div>
                <div>
                    <label style="font-size: 17px;" for="title"><?= trans('Title'); ?></label>
                </div>
                <div>
                    <input type="text"
                           name="title"
                           id="title"
                           placeholder="<?= trans('Title'); ?>"
                           value="<?= $formValues['title']; ?>">
                </div>

                <?php if (isset($errors['title'])) : ?>
                    <div style="color: red;">
                        <?= $errors['title']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <div>
                    <label style="font-size: 17px;" for="description"><?= trans('Description'); ?></label>
                </div>
                <div>
                    <input type="text"
                           name="description"
                           id="description"
                           placeholder="<?= trans('Description'); ?>"
                           value="<?= $formValues['description']; ?>">
                </div>

                <?php if (isset($errors['description'])) : ?>
                    <div style="color: red;">
                        <?= $errors['description']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <div>
                    <label style="font-size: 17px;" for="price"><?= trans('Price'); ?></label>
                </div>
                <div>
                    <input type="text"
                           name="price"
                           id="price"
                           placeholder="<?= trans('Price'); ?>"
                           value="<?= $formValues['price']; ?>">
                </div>

                <?php if (isset($errors['price'])) : ?>
                    <div style="color: red;">
                        <?= $errors['price']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div>
                <div>
                    <label for="image_file"><?= trans('Image'); ?></label>
                </div>

                <?php if ($action === 'edit') : ?>
                    <img src="<?= getImagePath($editedProduct); ?>"
                         alt="<?= trans('product_image'); ?>"
                         style="width: 100px; height: 100px;">
                <?php endif; ?>

                <div>
                    <input type="file" name="image_file" id="image_file">
                </div>

                <?php if (isset($errors['image_file'])) : ?>
                    <div style="color: red;">
                        <?= $errors['image_file']; ?>
                    </div>
                <?php elseif (isset($errors['image_size'])) : ?>
                    <div style="color: red;">
                        <?= $errors['image_size']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <a href="products.php" style="font-size: large;"><?= trans('Products'); ?></a>

            <button type="submit" name="save" style="margin-left: 25%;"><?= trans('Save'); ?></button>
        </form>
    </div>

<?php require_once 'includes/footer.php'; ?>