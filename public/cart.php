<?php

require_once 'common.php';

$pdo = pdoConnectMysql();

if (!isset($_SESSION['cart']) || !is_array($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

//List products from cart
$products = [];

if (count($_SESSION['cart']) > 0) {
    $arrayToQuestionMarks = implode(',', array_fill(0, count($_SESSION['cart']), '?'));

    $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN ($arrayToQuestionMarks)");
    // We only need the array keys, not the values, the keys are the id's of the products
    $stmt->execute(array_values($_SESSION['cart']));
    // Fetch the products from the database and return the result as an Array
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

//Add To Cart
if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
    $productId = (int) $_POST['product_id'];

    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$productId]);

    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($product) && !in_array($productId, $_SESSION['cart'])) {
        $_SESSION['cart'][] = $productId;
    }

    header('Location: index.php');
    exit();
}

// Remove products from cart
if (isset($_POST['product_id_to_remove']) && is_numeric($_POST['product_id_to_remove']) && count($_SESSION['cart']) > 0) {
    foreach ($_SESSION['cart'] as $index => $productInCartId) {
        if ((int) $productInCartId === (int) $_POST['product_id_to_remove']) {
            unset($_SESSION['cart'][$index]);

            header('Location: cart.php');
            exit();
        }
    }
}

//Checkout
if (isset($_POST['checkout'])) {
    $errors = [];

    if (!validateRequiredInput('customer_name')) {
        $errors['customer_name'] = 'Name field is required.';
    }

    if (!validateRequiredInput('customer_details')) {
        $errors['customer_details'] = 'Contact details field is required.';
    }

    if (!validateRequiredInput('customer_comments')) {
        $errors['customer_comments'] = 'Comments field is required.';
    }

    if (!$errors) {
        $totalPrice = 0;

        foreach ($products as $product) {
            $totalPrice += $product['price'];
        }

        $data = [
            'customer_name' => strip_tags($_POST['customer_name']),
            'customer_details' => strip_tags($_POST['customer_details']),
            'customer_comments' => strip_tags($_POST['customer_comments']),
            'creation_date' => date('Y-m-d H:i:s'),
            'product_price_sum' => $totalPrice,
        ];

        $subject = 'Checkout Order';

        $headers = 'From: '.FROM_EMAIL."\r\n";
        $headers .= "MIME-Version: 1.0\r\n";
        $headers .= "Content-Type: text/html; charset=UTF-8\r\n";

        ob_start();
        require 'content-email.php';
        $htmlContent = ob_get_clean();

        // Send email
        if (mail(TO_EMAIL, $subject, $htmlContent, $headers)) {
            $_SESSION['cart'] = [];
        }
    }

    // Create the order.
    $stmt = $pdo->prepare('INSERT INTO orders (customer_name, customer_details, customer_comments, creation_date, 
                           product_price_sum) VALUES  (?, ?, ?, ?, ?)');

    $stmt->execute([
        $data['customer_name'],
        $data['customer_details'],
        $data['customer_comments'],
        $data['creation_date'],
        $data['product_price_sum'],
    ]);

    $orderId = $pdo->lastInsertId();

    foreach ($products as $product) {
        $stmt = $pdo->prepare('INSERT INTO order_product (order_id, product_id) VALUES (?, ?)');

        $stmt->execute([
            (int) $orderId,
            (int) $product['id'],
        ]);
    }

    header('Location: cart.php');
    exit();
}

?>

<?php require_once 'includes/header.php'; ?>

<div style="border: 1px solid black; width: 600px; height: auto;">
    <h3 style="text-align: center"><?= trans('Cart'); ?></h3>

    <?php foreach ($products as $product) : ?>
        <div style="border: 1px solid black; margin: 10px; display: flex; align-items: center;
                                         justify-content: space-evenly;">

            <img src="<?= getImagePath($product); ?>" alt="<?= trans('product_image'); ?>"
                 style="width: 100px; height: 100px;">

            <div>
                <span><b><?= trans('Title:'); ?></b></span>
                <span><?= $product['title']; ?></span> <br>

                <span><b><?= trans('Description:'); ?></b></span>
                <span><?= $product['description']; ?></span> <br>

                <span><b><?= trans('Price:'); ?></b></span>
                <span><?= $product['price']; ?></span> <br>
            </div>

            <form action="cart.php" method="POST">
                <input type="hidden" name="product_id_to_remove" value="<?= $product['id']; ?>">

                <button type="submit"><?= trans('Remove'); ?></button>
            </form>
        </div>
    <?php endforeach; ?>

    <div style="margin: 30px;">
        <form action="cart.php" method="POST">
            <div style="display: grid">
                <input name="customer_name"
                       placeholder="<?= trans('Name'); ?>"
                       value="<?= isset($_POST['customer_name']) ? $_POST['customer_name'] : ''; ?>">

                <?php if (isset($errors['customer_name'])) : ?>
                    <div style="color: red;">
                        <?= $errors['customer_name']; ?>
                    </div>
                <?php endif; ?>

                <br>

                <input name="customer_details"
                       placeholder="<?= trans('Contact details'); ?>"
                       value="<?= isset($_POST['customer_details']) ? $_POST['customer_details'] : ''; ?>"
                       style="height: 30px;">

                <?php if (isset($errors['customer_details'])) : ?>
                    <div style="color: red;">
                        <?= $errors['customer_details']; ?>
                    </div>
                <?php endif; ?>

                <br>

                <input name="customer_comments"
                       placeholder="<?= trans('Comments'); ?>"
                       value="<?= isset($_POST['customer_comments']) ? $_POST['customer_comments'] : ''; ?>"
                       style="height: 40px;">

                <?php if (isset($errors['customer_comments'])) : ?>
                    <div style="color: red;">
                        <?= $errors['customer_comments']; ?>
                    </div>
                <?php endif; ?>

                <br>
            </div>

            <div style="float: right;">
                <a href="index.php"><?= trans('Go to index'); ?></a>

                <button type="submit" name="checkout"><?= trans('Checkout'); ?></button>
            </div>
        </form>
    </div>

</div>

<?php require_once 'includes/footer.php'; ?>





