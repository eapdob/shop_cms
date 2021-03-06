<?php

namespace app\models;

use RedBeanPHP\R;

class User extends AppModel {

    public $attributes = [
        'login' => '',
        'password' => '',
        'name' => '',
        'email' => '',
        'address' => ''
    ];

    public $rules = [
        'required' => [
            ['login'],
            ['password'],
            ['name'],
            ['email'],
            ['address']
        ],
        'email' => [
            ['email']
        ],
        'lengthMin' => [
            ['password', 6]
        ]
    ];

    public function checkUnique() {
        $user = R::findOne('user', 'login = ? OR email = ?', [$this->attributes['login'], $this->attributes['email']]);
        if ($user) {
            if ($user->login == $this->attributes['login']) {
                $this->errors['unique'][] = 'This login is already taken';
            }
            if ($user->email == $this->attributes['email']) {
                $this->errors['unique'][] = 'This email is already taken';

            }
            return false;
        }
        return true;
    }

    public function login($data = [], $isAdmin = false) {
        if (!empty($data)) {
            $login = !empty(trim($data['login'])) ? trim($data['login']) : null;
            $password = !empty(trim($data['password'])) ? trim($data['password']) : null;
            if ($login && $password) {
                if ($isAdmin) {
                    $user = R::findOne('user', "login = ? AND role = 'admin'", [$login]);
                } else {
                    $user = R::findOne('user', "login = ?", [$login]);
                }
                if ($user) {
                    if (password_verify($password, $user->password)) {
                        foreach ($user as $k => $v) {
                            if ($k != 'password') {
                                $_SESSION['user'][$k] = $v;
                            }
                        }
                        return true;
                    }
                }
            }
        }
        return false;
    }

    public function changeRulesForCheckout() {
        $this->rules = [
            'required' => [
                ['name'],
                ['email'],
                ['address']
            ],
            'email' => [
                ['email']
            ],
            'lengthMin' => [
                ['password', 6]
            ]
        ];
    }

    public static function checkAuth() {
        return isset($_SESSION['user']);
    }

    public static function isAdmin() {
        return (isset($_SESSION['user']) && $_SESSION['user']['role'] == 'admin');
    }
}