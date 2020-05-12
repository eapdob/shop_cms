<?php

namespace app\controllers\admin;

use RedBeanPHP\R;
use shop\App;
use shop\libs\Pagination;

class OrderController extends AppController {

    public function indexAction() {
        // pagination
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $perPage = App::$app->getProperty('pagination');
        $count = R::count('order');
        $pagination = new Pagination($page, $perPage, $count);
        $start = $pagination->getStart();

        // order
        $sql = "(SELECT 
                `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, 
                `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) 
            AS `sum` 
            FROM `order` 
            LEFT JOIN `user` 
            ON `order`.`user_id` = `user`.`id` 
            LEFT JOIN `order_product` 
            ON `order`.`id` = `order_product`.`order_id` 
            WHERE `order`.`user_id` = '0' GROUP BY `order`.`id` 
            ORDER BY `order`.`id`) UNION
            (SELECT 
                `order`.`id`, `order`.`user_id`, `order`.`status`, `order`.`date`, `order`.`update_at`, `order`.`currency`, 
                `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) 
            AS `sum` 
            FROM `order` 
            JOIN `user` 
            ON `order`.`user_id` = `user`.`id` 
            JOIN `order_product` 
            ON `order`.`id` = `order_product`.`order_id`
            GROUP BY `order`.`id` 
            ORDER BY `order`.`status`, `order`.`id`) LIMIT $start, $perPage";
        $orders = R::getAll($sql);

        $this->setMeta('Orders list');
        $this->set(compact('orders', 'pagination', 'count'));
    }

    public function viewAction() {
        // order
        $orderId = $this->getRequestID();
        $sql = "
            (SELECT 
                `order`.*,`user`.`name`, ROUND(SUM(`order_product`.`price`), 2) 
            AS `sum` 
            FROM `order` 
            LEFT JOIN `user` 
            ON `order`.`user_id` = `user`.`id` 
            LEFT JOIN `order_product` 
            ON `order`.`id` = `order_product`.`order_id` 
            WHERE `order`.`user_id` = '0' AND `order`.`id` = ? GROUP BY `order`.`id` 
            ORDER BY `order`.`id`) 
            UNION 
            (SELECT 
                `order`.*, `user`.`name`, ROUND(SUM(`order_product`.`price`), 2) 
            AS `sum` 
            FROM `order` 
            JOIN `user` 
            ON `order`.`user_id` = `user`.`id` 
            JOIN `order_product` 
            ON `order`.`id` = `order_product`.`order_id`
            WHERE `order`.`id` = ?
            GROUP BY `order`.`id` 
            ORDER BY `order`.`status`, `order`.`id`) LIMIT 1";
        $order = R::getRow($sql, [$orderId, $orderId]);
        if (!$order) {
            throw new \Exception('Page not found', 404);
        }

        // products
        $orderProducts = R::findAll('order_product', "order_id = ?", [$order['id']]);

        $this->setMeta("Order #{$orderId}");
        $this->set(compact('order', 'orderProducts'));
    }

    public function changeAction() {
        $orderId = $this->getRequestID();
        $status = (!empty($_GET['status'])) ? '1' : '0';

//        $order = R::load('order', $orderId);
//        if (!$order) {
//            throw new \Exception('Page not found', 404);
//        }
//        $order->status = $status;
//        $order->update_at = date('Y-m-d H:i:s');
//        R::store($order);

        $update_at = date('Y-m-d H:i:s');
        $sql = "UPDATE `order` SET status = '{$status}', update_at = '{$update_at}' WHERE id = ?";
        $result = R::exec($sql, [$orderId]);
        if ($result) {
            $_SESSION['success'] = 'Changes saved';
        }
        redirect();
    }

    public function deleteAction() {
        $orderId = $this->getRequestID();

        $order = R::load('order', $orderId);
        R::trash($order);
        $_SESSION['success'] = 'Order has been removed';
        redirect(ADMIN . '/order');
    }
}