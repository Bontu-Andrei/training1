<?php

require_once "common.php";

$session = session();

$pdo = pdoConnectMysql();

if (isset($_POST["checkout"])) {

    $image_path = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

    $products = getAllProductsFromCart();

    $checkoutDate = date("Y-m-d H:i:s");

    $data = [
        "customer_name" => $_POST["customer_name"],
        "customer_details" => $_POST["customer_details"],
        "customer_comments" => $_POST["customer_comments"],
        "creation_date" => $checkoutDate,
    ];

    $headers = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

    $to = SHOP_MANAGER_EMAIL;
    $from = SHOP_MANAGER_EMAIL;

    $subject = "Checkout";

    $htmlContent = " 
        <html> 
        <head> 
            <title>".trans('Checkout')."</title> 
        </head> 
        <body> 
            <h1>". trans('Thanks for your order, ').$data['customer_name']."</h1>";
            foreach ($products as $product) {
                $htmlContent .= "
                    <table cellspacing='0' style='border: 2px dashed #FB4314; width: 100%;'>
                        <tr>
                            <img src=".$image_path. '/images/default.jpg'." alt=".trans('product_image')."
                                style='width: 100px;' height='100px;'>
                        </tr>
                        
                        <tr>
                            <th>".trans('Title')."</th>
                            <td>".$product['title']."</td>
                        </tr>
                        
                        <tr style='background-color: #e0e0e0;'>
                            <th>".trans('Description')."</th>
                            <td>".$product['description']."</td>
                        </tr>
                        
                        <tr>
                            <th>".trans('Price')."</th>
                            <td>".$product['price']."</td>
                        </tr>
                    </table>";
            }
            $htmlContent .= "
                <h3>".trans('Contact details:').$data['customer_details']."</h3>
                <h3>".trans('Comments:').$data['customer_comments']."</h3>
                <h3>".trans('Created at:').$data['creation_date']."</h3>";

            $htmlContent .= "</body>
        </html>";

    // Send email
    if (mail($to, $subject, $htmlContent, $headers)) {
        echo 'Email has sent successfully.';
    } else {
        echo 'Email sending failed.';
    }
}






