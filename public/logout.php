<?php

require_once "common.php";

$session = session();

unset($_SESSION["logged_in"]);

header("Location: login.php");
exit();
