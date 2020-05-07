<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Category;
use RedBeanPHP\R;
use shop\App;
use shop\libs\Pagination;

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

        // pagination
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $perPage = App::$app->getProperty('pagination');
        $total = R::count('product', "category_id IN ($ids)");
        $pagination = new Pagination($page, $perPage, $total);
        $start = $pagination->getStart();

        // products
        $products = R::find('product', "category_id IN ($ids) LIMIT $start, $perPage");

        // meta
        $this->setMeta($category->title, $category->description, $category->keywords);
        $this->set(compact('products', 'breadcrumbs', 'pagination', 'total'));
    }
}