<?php

require_once 'common.php';

if (isset($_POST['checkout'])) {
    $headers = 'MIME-Version: 1.0'."\r\n";
    $headers .= 'Content-Type: text/html; charset=ISO-8859-1'."\r\n";
    $subject = 'Checkout Order';

    $to = SHOP_MANAGER_EMAIL;
    $from = SHOP_MANAGER_EMAIL;

    ob_start();
    require_once 'content-email.php';
    $htmlContent = ob_get_clean();

    // Send email
    if (mail($to, $subject, $htmlContent, $headers)) {
        echo trans('Email has sent successfully.');
    } else {
        echo trans('Email sending failed.');
    }
}
