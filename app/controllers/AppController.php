<?php

namespace app\controllers;

use shop\base\Controller;
use app\models\AppModel;
use shop\App;
use app\widgets\currency\Currency;

class AppController extends Controller {

    public function __construct($route) {
        parent::__construct($route);
        new AppModel();
        App::$app->setProperty('currencies', Currency::getCurrencies());
        App::$app->setProperty('currency', Currency::getCurrency(App::$app->getProperty('currencies')));
    }
}