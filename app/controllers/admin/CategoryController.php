<?php

namespace app\controllers\admin;

use app\models\AppModel;
use app\models\Category;
use RedBeanPHP\R;
use shop\App;

class CategoryController extends AppController {

    public function indexAction() {
        $this->setMeta('Categories list');
    }

    public function deleteAction() {
        $errors = '';
        $id = $this->getRequestID();

        $children = R::count('category', 'parent_id = ?', [$id]);
        if ($children) {
            $errors .= '<li>Deleting is impossible, category has childs categories</li>';
        }

        $product = R::count('product', 'category_id = ?', [$id]);
        if ($product) {
            $errors .= '<li>Deleting is impossible, category has products</li>';
        }

        if ($errors) {
            $_SESSION['error'] = "<ul>$errors</ul>";
            redirect();
        }

        $category = R::load('category', $id);
        R::trash($category);
        $_SESSION['success'] = 'Category deleted';

        redirect();
    }

    public function addAction() {
        if (!empty($_POST)) {
            $category = new Category();
            $data = $_POST;
            $category->load($data);
            if (!$category->validate($data)) {
                $category->getErrors();
                $_SESSION['form_data'] = $data;
                redirect();
            }
            if ($id = $category->save('category')) {
                $alias = AppModel::createAlias('category', 'alias', $data['title'], $id);

                $cat = R::load('category', $id);
                $cat->alias = $alias;
                R::store($cat);

                $_SESSION['success'] = 'Category added';
            }
            redirect();
        }

        $this->setMeta('Add category');
    }

    public function editAction() {
        if (!empty($_POST)) {
            $id = $this->getRequestId(false);
            $category = new Category();
            $data = $_POST;
            $category->load($data);
            if (!$category->validate($data)) {
                $category->getErrors();
                redirect();
            }
            if ($category->update('category', $id)) {
                $alias = AppModel::createAlias('category', 'alias', $data['title'], $id);
                $cat = R::load('category', $id);
                $cat->alias = $alias;
                R::store($cat);

                $_SESSION['success'] = 'Category updated';
            }
            redirect();
        }
        $id = $this->getRequestId();
        $category = R::load('category', $id);
        App::$app->setProperty('parent_id', $category->parent_id);
        $this->setMeta("Edit category {$category->title}");
        $this->set(compact('category'));
    }
}