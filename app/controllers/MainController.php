<?php

namespace app\controllers;

use \RedBeanPHP\R as R;

class MainController extends AppController {

    public function indexAction() {
        $brands = R::findAll('brand');
        $hits = R::find('product', "hit = '1' AND status = '1' LIMIT 8");
        $this->setMeta(
            'hello main description',
            'hello main description',
            'hello main keywords'
        );
        $this->set(compact('brands', 'hits'));
    }
}