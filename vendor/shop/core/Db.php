<?php

namespace shop;

use \RedBeanPHP\R as R;

class Db extends \RedBeanPHP\SimpleModel {

    use TSingleton;

    protected function __construct() {
        $db = require_once CONF . '/config_db.php';

        R::setup($db['dsn'], $db['user'], $db['pass']);

        if (!R::testConnection()) {
            throw New \Exception("Нет соединения с бд", 500);
        }

        R::freeze(true);

        if (DEBUG) {
            R::debug(true, 1);
        }
    }
}