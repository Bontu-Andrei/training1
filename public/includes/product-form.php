<div style="display: flex; justify-content: center; margin-top: 10px;">
    <form action="../product.php" method="POST" enctype="multipart/form-data">
        <?= $action === 'edit' ? '<input type="hidden" name="id" value="' . $editedProduct['id'] . '">' : '' ?>

        <div>
            <div>
                <label style="font-size: 17px;" for="title"><?= trans('Title'); ?></label>
            </div>
            <div>
                <input type="text"
                       name="title"
                       id="title"
                       placeholder="<?= trans('Title'); ?>"
                       value="<?= isset($_POST['title'])
                           ? $_POST['title']
                           : ($action === 'create' ? '' : $editedProduct['title']); ?>">
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
                       value="<?= isset($_POST['description'])
                           ? $_POST['description']
                           : ($action === 'create' ? '' : $editedProduct['description']); ?>">
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
                       value="<?= isset($_POST['price'])
                           ? $_POST['price']
                           : ($action === 'create' ? '' : $editedProduct['price']); ?>">
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
                <img src="/images/<?= $editedProduct['image_path'] ?>"
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
            <?php endif; ?>
        </div>

        <?php if (isset($errors['error'])) : ?>
            <div style="color: red;">
                <?= $errors['error']; ?>
            </div>
        <?php endif; ?>

        <a href="products.php" style="font-size: large;"><?= trans('Products'); ?></a>

        <button type="submit" style="margin-left: 25%;"><?= trans('Save'); ?></button>
    </form>
</div>
