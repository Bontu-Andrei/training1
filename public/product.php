<?php

require_once "common.php";

$session = session();

if (!$_SESSION["logged_in"]) {
    // not auth so we redirect.
    header("Location: login.php");
    exit();
}

$pdo = pdoConnectMysql();

//Add a new post
if (isset($_POST["save"]))  {
    //Upload image
    $response = uploadImage();

    if ( ! $response["success"]) {
        echo $response["error"];
        exit();
    }

    $data = [
        "title" => $_POST["title"],
        "description" => $_POST["description"],
        "price" => $_POST["price"],
        "image_path" => $response["filename"],
    ];

    $sql = "INSERT INTO products (title, description, price, image_path) VALUES (:title, :description, :price, :image_path)";

    $stmt = $pdo->prepare($sql);

    $stmt->execute($data);

    header("Location: products.php");
    exit();
}

?>

<?php include "includes/header.php"; ?>

<div style="display: flex; justify-content: center; font-size: xx-large;">
    <form action="<?= $_SERVER['PHP_SELF'] ?>" method="POST" enctype="multipart/form-data">
        <input type="text" name="title" placeholder="<?= trans("Title") ?>" required> <br>
        <input type="text" name="description" placeholder="<?= trans("Description") ?>" required> <br>
        <input type="text" name="price" placeholder="<?= trans("Price") ?>" required> <br>
        <input required type="file" name="image_file"> <br>

        <a href="products.php" style="font-size: large;"><?= trans("Products") ?></a>
        <button type="submit" name="save" style="margin-left: 25%;"><?= trans("Save") ?></button>
    </form>
</div>

<?php include "includes/footer.php"; ?>