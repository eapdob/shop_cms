<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use RedBeanPHP\R;

class CategoryController extends AppController {

    public function viewAction() {
        $alias = $this->route['alias'];
        $category = R::findOne('category', 'alias = ?', [$alias]);
        if (!$category) {
            throw new \Exception('Страница не найдена', 404);
        }

        // breadcrumbs
        $breadcrumbs = Breadcrumbs::getBreadcrumbs($category->id);

        // category
        $catModel = new Category();
        $ids = $catModel->getIds($category->id);
        $ids = !$ids ? $category->id : $ids . $category->id;

        // products
        $products = R::find('product', "category_id IN ($ids)");
        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->set(compact('products', 'breadcrumbs'));
    }
}