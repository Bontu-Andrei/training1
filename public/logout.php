<?php

require_once 'common.php';

unset($_SESSION['logged_in']);

header('Location: login.php');
exit();
