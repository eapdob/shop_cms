<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\User;
use shop\base\Controller;

class AppController extends Controller {

    public $layout = 'admin';

    public function __construct($route) {
        parent::__construct($route);
        new AppModel();

        if (!User::isAdmin() && $route['action'] != 'login-admin') {
            redirect(ADMIN . '/user/login-admin');
        }
    }

    public function getRequestID($get = true) {
        if ($get) {
            $data = $_GET;
        } else {
            $data = $_POST;
        }
        $id = !empty($data['id']) ? (int) $data['id'] : null;
        if (!$id) {
            throw new \Exception('Page not found', 404);
        }
        return $id;
    }
}