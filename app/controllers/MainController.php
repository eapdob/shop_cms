<?php

namespace app\controllers;

class MainController extends AppController {

    public function indexAction() {
        $this->setMeta(
            'hello main description',
            'hello main description',
            'hello main keywords'
        );
    }
}