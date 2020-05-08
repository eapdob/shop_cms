<?php

namespace app\models;

use RedBeanPHP\R as R;
use shop\App;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class Order extends AppModel {

    public static function saveOrder($data) {
        $order = R::dispense('order');
        $order->user_id = $data['user_id'];
        $order->note = $data['note'];
        $order->currency = $_SESSION['cart.currency']['code'];
        $order_id = R::store($order);
        self::saveOrderProduct($order_id);
        return $order_id;
    }

    public static function saveOrderProduct($order_id) {
        $sqlPart = '';
        foreach ($_SESSION['cart'] as $product_id => $product) {
            $product_id = (int) $product_id;
            $sqlPart .= "($order_id, $product_id, {$product['qty']}, '{$product['title']}', {$product['price']}),";
        }
        $sqlPart = rtrim($sqlPart, ',');
        R::exec("INSERT INTO order_product (order_id, product_id, qty, title, price) VALUES $sqlPart");
    }

    public static function mailOrder($order_id, $user_email) {
        // Create the Transport
        $transport = (new Swift_SmtpTransport(App::$app->getProperty('smtp_host'), App::$app->getProperty('smtp_port'), App::$app->getProperty('smtp_protocol')))
            ->setUsername(App::$app->getProperty('smtp_login'))
            ->setPassword(App::$app->getProperty('smtp_password'));

        // Create the Mailer using your created Transport
        $mailer = new Swift_Mailer($transport);

        // Create a message
        ob_start();
        require APP . '/views/mail/mail_order.php';
        $body = ob_get_clean();

        $messageClient = (new Swift_Message("Вы совершили Заказ № {$order_id} на сайте " . App::$app->getProperty('shop_name') . ""))
            ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
            ->setTo([$user_email])
            ->setBody($body, 'text/html');

        $messageAdmin = (new Swift_Message("Сделан заказ № {$order_id}"))
            ->setFrom([App::$app->getProperty('smtp_login') => App::$app->getProperty('shop_name')])
            ->setTo([App::$app->getProperty('admin_email')])
            ->setBody($body, 'text/html');

        // Send the message
        $resultClient = $mailer->send($messageClient);
        $resultAdmin = $mailer->send($messageAdmin);
        if ($resultClient && $resultAdmin) {
            unset($_SESSION['cart']);
            unset($_SESSION['cart.qty']);
            unset($_SESSION['cart.sum']);
            unset($_SESSION['cart.currency']);
            $_SESSION['success'] = 'Спасибо за Ваш заказ. В ближайшее время с вами свяжется менеджер для согласования заказа';
        }
    }
}