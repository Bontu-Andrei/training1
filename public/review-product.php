<?php

require_once 'common.php';

$pdo = pdoConnectMysql();

if (isset($_GET['id']) && is_numeric($_GET['id']) && !empty($_GET['id'])) {
    $id = (int) $_GET['id'];

    $stmt = $pdo->prepare('SELECT * FROM products WHERE id = ?');
    $stmt->execute([$id]);
    $product = $stmt->fetch(PDO::FETCH_ASSOC);

    if (empty($product)) {
        exit('No product.');
    }

    $sql = 'SELECT * FROM reviews INNER JOIN product_review
            ON reviews.id = product_review.review_id
            WHERE product_review.product_id = ?';
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$id]);
    $reviewProduct = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

if (isset($_POST['review'])) {
    $errors = [];

    if (!isset($_POST['note'])) {
        $errors['note'] = 'You are not selected any note.';
    }

    if (!validateRequiredInput('title')) {
        $errors['title'] = 'Title field is required.';
    }

    if (!validateRequiredInput('description')) {
        $errors['password'] = 'Password field is required.';
    }

    if (!$errors) {
        $data = [
            strip_tags($_POST['note']),
            strip_tags($_POST['title']),
            strip_tags($_POST['description']),
        ];

        $sql = 'INSERT INTO reviews (note, title, description) VALUES (?, ?, ?)';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($data);

        $reviewId = $pdo->lastInsertId();

        $stmt = $pdo->prepare('INSERT INTO product_review (product_id, review_id) VALUES (?, ?)');

        $productId = strip_tags($_POST['product_id']);

        $stmt->execute([
            (int) $productId,
            (int) $reviewId,
        ]);
    }

    header('Location: review-product.php?id='.$productId);
    exit();
}

if (isset($_POST['review_id_to_remove']) && $_POST['review_id_to_remove']) {
    $id = (int) $_POST['review_id_to_remove'];

    $stmt = $pdo->prepare('DELETE FROM product_review WHERE id = ?');
    $stmt->execute([$id]);

    header('Location: index.php');
    exit();
}

?>

<?php require_once 'includes/header.php'; ?>
<div style="border: 1px solid black; width: 600px; height: 120px; margin: 10px auto; display: flex;
                                 align-items: center; justify-content: space-evenly;">

    <img src="<?php echo getImagePath($product); ?>" alt="<?= trans('product_image'); ?>"
         style="width: 100px; height: 100px;">

    <div>
        <span><b><?= trans('Title:'); ?></b></span>
        <span><?= $product['title']; ?></span> <br>

        <span><b><?= trans('Description:'); ?></b></span>
        <span><?= $product['description']; ?></span> <br>

        <span><b><?= trans('Price:'); ?></b></span>
        <span><?= $product['price']; ?></span> <br>
    </div>
</div>

<?php foreach ($reviewProduct as $review) : ?>
    <div style="width: 600px; height: auto; margin: 10px auto; border: 1px solid black;">
        <div>
            <span><b><?= trans('Note:'); ?></b></span>
            <span><?= $review['note']; ?></span>
        </div>

        <div>
            <span><b><?= trans('Title:'); ?></b></span>
            <span> <?= $review['title']; ?> </span>
        </div>

        <div>
            <span><b><?= trans('Description'); ?></b></span>
            <span> <?= $review['description']; ?> </span>
        </div>

        <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in']) : ?>
            <div>
                <form action="review-product.php" method="POST">
                    <input type="hidden" name="review_id_to_remove" value="<?= $review['id']; ?>">

                    <button type="submit"><?= trans('Delete review'); ?></button>
                </form>
            </div>
        <?php endif; ?>
    </div>
<?php endforeach; ?>

<div style="width: 600px; margin: 10px auto; border: 1px solid black; height: auto; display: flex; justify-content: center;">
    <form action="review-product.php" method="POST" style="display: grid; justify-content: center;">
        <input type="hidden" name="product_id" id="product_id" value="<?= $product['id']; ?>">
        <div>
            <div>
                <label for="note"><?= trans('Note:'); ?></label>
            </div>

            <div>
                <input type="radio" name="note" id="note" value="5"/> 5
                <input type="radio" name="note" id="note" value="4"/> 4
                <input type="radio" name="note" id="note" value="3"/> 3
                <input type="radio" name="note" id="note" value="2"/> 2
                <input type="radio" name="note" id="note" value="1"/> 1
            </div>

            <?php if (isset($errors['note'])) : ?>
                <div style="color: red;">
                    <?= $errors['note']; ?>
                </div>
            <?php endif; ?>
        </div>

        <br>

        <div>
            <div>
                <label for="title"><?= trans('Title:'); ?></label>
            </div>

            <div>
                <input type="text"
                       id="title"
                       name="title"
                       value="<?= isset($_POST['title']) ? $_POST['title'] : ''; ?>"
                       placeholder="<?= trans('Title'); ?>">
            </div>

            <?php if (isset($errors['title'])) : ?>
                <div style="color: red;">
                    <?= $errors['title']; ?>
                </div>
            <?php endif; ?>
        </div>

        <br>

        <div>
            <div>
                <label for="description"><?= trans('Description:'); ?></label>
            </div>

            <div>
                <input name="description"
                       id="description"
                       value="<?= isset($_POST['description']) ? $_POST['description'] : ''; ?>"
                       placeholder="<?= trans('Description'); ?>">
            </div>

            <?php if (isset($errors['description'])) : ?>
                <div style="color: red;">
                    <?= $errors['description']; ?>
                </div>
            <?php endif; ?>
        </div>

        <br>

        <div>
            <button type="submit" name="review"><?= trans('Add review'); ?></button>
        </div>
    </form>
</div>
<?php require_once 'includes/footer.php'; ?>


