<?php

namespace shop\base;

use shop\Db;
use Valitron\Validator;
use RedBeanPHP\R;

abstract class Model {

    public $attributes = [];
    public $errors = [];
    public $rules = [];

    public function __construct() {
        Db::instance();
    }

    public function load($data) {
        foreach ($this->attributes as $name => $value) {
            if (isset($data[$name])) {
                $this->attributes[$name] = $data[$name];
            }
        }
    }

    public function save($table) {
        $tbl = R::dispense($table);
        foreach ($this->attributes as $name => $value) {
            $tbl->$name = $value;
        }
        return R::store($tbl);
    }

    public function update($table, $id) {
        $bean = R::load($table, $id);
        foreach ($this->attributes as $name => $value) {
            $bean->$name = $value;
        }
        return R::store($bean);
    }

    public function validate($data) {
        Validator::langDir(WWW . '/validator/lang');
        Validator::lang('ru');
        $vLang = require_once Validator::langDir() . '/' . Validator::lang() . '.php';
        $v = new Validator($data);
        $v->rules($this->rules);
        $v->labels(array(
            'login' => $vLang['login_name'],
            'password' => $vLang['password_name'],
            'name' => $vLang['name_name'],
            'email' => $vLang['email_name'],
            'address' => $vLang['address_name']
        ));
        if ($v->validate()) {
            return true;
        }
        $this->errors = $v->errors();
        return false;
    }

    public function getErrors() {
        $errors = '<ul>';
        foreach ($this->errors as $error) {
            foreach ($error as $item) {
                $errors .= '<li>'. $item . '</li>';
            }
        }
        $errors .= '</ul>';
        $_SESSION['error'] = $errors;
    }
}