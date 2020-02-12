<div style="display: flex; justify-content: center; margin-top: 10px;">
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
        <?= $action === 'edit' ? '<input type="hidden" name="id" value="' . $editedProduct['id'] . '">' : '' ?>

        <div>
            <div>
                <label style="font-size: 17px;" for="title"><?= trans('Title'); ?></label>
            </div>
            <div>
                <input style="margin-bottom: 10px;"
                       type="text"
                       name="title"
                       id="title"
                       placeholder="<?= trans('Title'); ?>"
                       value="<?= isset($_POST['title'])
                           ? $_POST['title']
                           : ($action === 'create' ? '' : $editedProduct['title']); ?>"> <br>
            </div>
            <div style="color: red;">
                <?= isset($_SESSION['errors']['title']) ? $_SESSION['errors']['title'] : '' ?>
            </div>
        </div>

        <div>
            <div>
                <label style="font-size: 17px;" for="description"><?= trans('Description'); ?></label>
            </div>
            <div>
                <input style="margin-bottom: 10px;"
                       type="text"
                       name="description"
                       id="description"
                       placeholder="<?= trans('Description'); ?>"
                       value="<?= isset($_POST['description'])
                           ? $_POST['description']
                           : ($action === 'create' ? '' : $editedProduct['description']); ?>"> <br>
            </div>
            <div style="color: red;">
                <?= isset($_SESSION['errors']['description']) ? $_SESSION['errors']['description'] : '' ?>
            </div>
        </div>

        <div>
            <div>
                <label style="font-size: 17px;" for="price"><?= trans('Price'); ?></label>
            </div>
            <div>
                <input style="margin-bottom: 10px;"
                       type="text"
                       name="price"
                       id="price"
                       placeholder="<?= trans('Price'); ?>"
                       value="<?= isset($_POST['price'])
                           ? $_POST['price']
                           : ($action === 'create' ? '' : $editedProduct['price']); ?>"> <br>
            </div>
            <div style="color: red;">
                <?= isset($_SESSION['errors']['price']) ? $_SESSION['errors']['price'] : '' ?>
            </div>
        </div>

        <div>
            <div>
                <label for="image_file"><?= trans('Image'); ?></label>
            </div>

            <?php if ($action === 'edit') : ?>
                <img src="/images/<?= $editedProduct['image_path'] ? $editedProduct['image_path'] : 'default.jpg' ?>"
                     alt="<?= trans('product_image') ?>"
                     style="width: 100px; height: 100px;">
            <?php endif; ?>

            <div>
                <input type="file" name="image_file" id="image_file"> <br>
            </div>

            <div style="color: red;">
                <?= isset($_SESSION['errors']['image_file']) ? $_SESSION['errors']['image_file'] : '' ?>
            </div>
        </div>

        <div style="color: red;">
            <?= isset($_SESSION['errors']['error']) ? $_SESSION['errors']['error'] : '' ?>
        </div>

        <br>

        <a href="products.php" style="font-size: large;"><?= trans('Products') ?></a>

        <button type="submit" style="margin-left: 25%;"><?= trans('Save') ?></button>
    </form>
</div>
