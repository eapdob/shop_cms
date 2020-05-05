<?php

namespace app\controllers;

use app\models\Breadcrumbs;
use app\models\Product;
use RedBeanPHP\R as R;

class ProductController extends AppController {
    public function viewAction() {
        $alias = $this->route['alias'];
        $product = R::findOne('product', "alias = ? AND status = '1'", [$alias]);
        if (!$product) {
            throw new \Exception('Страница не найдена', 404);
        }

        // breadcrumbs
        $breadcrumbs = Breadcrumbs::getBreadcrumbs($product->category_id, $product->title);

        // related products
        $related = R::getAll("SELECT * FROM related_product JOIN product ON related_product.product_id = product.id WHERE related_product.product_id = ? ", [$product->id]);

        // write to cookie current product
        $pModel = new Product();
        $pModel->setRecentlyViewed($product->id);

        // viewed product
        $rViewed = $pModel->getRecentlyViewed();
        $recentlyViewed = null;
        if ($rViewed) {
            $recentlyViewed = R::find('product', 'id IN (' . R::genSlots($rViewed) . ') LIMIT 3', $rViewed);
        }

        // gallery
        $gallery = R::findAll('gallery', 'product_id = ?', [$product->id]);

        // modification
        $modification = R::findAll('modification', 'product_id = ?', [$product->id]);

        // meta
        $this->setMeta($product->title, $product->description, $product->keywords);

        $this->set(compact('product', 'related', 'gallery', 'recentlyViewed', 'breadcrumbs', 'modification'));
    }
}