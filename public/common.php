<?php

require_once "config.php";

function pdoConnectMysql() {
    try {
        return new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
    } catch (PDOException $exception) {
        die ('Failed to connect to database!');
    }
}

function trans($name) {
    return $name;
}

function getAllProductsFromCart() {
    $pdo = pdoConnectMysql();

    //List products from cart
    $productsInCart = isset($_SESSION['cart']) ? $_SESSION['cart'] : array();
    $products = [];

    if (count($productsInCart) > 0) {
        // Products in cart array to question mark string array, we need the SQL statement to include IN (?,?,?,...etc)
        $arrayToQuestionMarks = implode(",", array_fill(0, count($productsInCart), "?"));

        $stmt = $pdo->prepare("SELECT * FROM products WHERE id IN (" . $arrayToQuestionMarks . ")");
        // We only need the array keys, not the values, the keys are the id's of the products
        $stmt->execute(array_values($productsInCart));
        // Fetch the products from the database and return the result as an Array
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    return $products;
}

function template_header($title) {
    echo <<<EOT
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <title>$title</title>
    </head>
    <body>
        <header style="background-color: #9e9e9eb5">
            <div>
                <h1 style="text-align: center">Training</h1>
                <nav>
                    <h4><a href="index.php">Index</a></h4>
                    <h4><a href="cart.view.php">Cart</a></h4>
                    <h4><a href="products.php">Products</a></h4>
                </nav>
            </div>
        </header>
        <main>
EOT;
}
// Template footer
function template_footer() {
    $year = date('Y');
    echo <<<EOT
        </main>
        <footer>
            <div style="text-align: center">
                <p>&copy; $year, Training</p>
            </div>
        </footer>
    </body>
</html>
EOT;
}
?>