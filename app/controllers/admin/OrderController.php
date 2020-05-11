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
}