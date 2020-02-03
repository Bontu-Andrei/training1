<?php

require_once 'config.php';

function pdo_connect_mysql() {
    try {
        return new PDO('mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8', DB_USER, DB_PASSWORD);
    } catch (PDOException $exception) {
        die ('Failed to connect to database!');
    }
}

function trans($name) {
    return $name;
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