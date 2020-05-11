<?php

namespace shop;

use RedBeanPHP\R;

class Db extends \RedBeanPHP\SimpleModel {

    use TSingleton;

    protected function __construct() {
        $db = require_once CONF . '/config_db.php';

        R::setup($db['dns'], $db['user'], $db['pass']);

        if (!R::testConnection()) {
            throw New \Exception("No connection to db", 500);
        }

        R::freeze(true);

        if (DEBUG) {
            R::debug(true, 1);
        }
    }
}