<?php

namespace app\controllers\admin;

use app\models\User;
use shop\base\Controller;

class UserController extends AppController {

    public function loginAdminAction() {
        if (User::isAdmin()) {
            redirect(ADMIN);
        }

        if (!empty($_POST)) {
            $data = $_POST;
            $user = new User();
            if (!$user->login($data, true)) {
                $_SESSION['error'] = 'The username / password you entered is incorrect';
            }
            if (User::isAdmin()) {
                redirect(ADMIN);
            } else {
                redirect();
            }
        }

        $this->layout = 'login';
    }
}