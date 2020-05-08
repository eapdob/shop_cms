<?php

namespace app\controllers;

use app\models\User;
use RedBeanPHP\R as R;

class UserController extends AppController {

    public function signupAction() {
        if (!empty($_POST)) {
            $user = new User();
            $data = $_POST;
            $user->load($data);
            if (!$user->validate($data) || !$user->checkUnique()) {
                $user->getErrors();
                $_SESSION['form_data'] = $data;
            } else {
                $user->attributes['password'] = password_hash($user->attributes['password'], PASSWORD_DEFAULT);
                if ($id = $user->save('user')) {
                    $_SESSION['success'] = 'Пользователь зарегистрирован';
                    $u = R::findOne('user', "id = $id");
                    if ($u) {
                        if ($user->login(['login' => $data['login'], 'password' => $data['password']])) {
                            redirect(PATH);
                        }
                    }
                } else {
                    $_SESSION['error'] = 'Ошибка!';
                }
            }
            redirect();
        }
        $this->setMeta('Регистрация');
    }

    public function loginAction() {
        if (!empty($_POST)) {
            $user = new User();
            $data = $_POST;
            if ($user->login($data)) {
                $_SESSION['success'] = 'Вы успешно авторизованы';
            } else {
                $_SESSION['error'] = 'Логин и пароль введены неверно';
            }
            redirect();
        }
        $this->setMeta('Вход');
    }

    public function logoutAction() {
        if (isset($_SESSION['user'])) unset($_SESSION['user']);
        redirect();
    }
}