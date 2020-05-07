<?php

namespace app\models;

use shop\App;

class Breadcrumbs {

    public static function getBreadcrumbs($categoryId, $name = '') {
        $cats = App::$app->getProperty('cats');
        $breadcrumbsArray = self::getParts($cats, $categoryId);
        $breadcrumbs = "<li><a href='" . PATH . "'>Главная</a></li>";

        if ($breadcrumbsArray) {
            if ($name == '') {
                $index = 0;
                $lastIndex = count($breadcrumbsArray) - 1;
            }
            foreach ($breadcrumbsArray as $alias => $title) {
                if ($lastIndex != $index) {
                    $breadcrumbs .= "<li><a href='" . PATH . "category/{$alias}'>{$title}</a></li>";
                } else {
                    $breadcrumbs .= "<li>{$title}</li>";
                }
                $index++;
            }
        }

        if ($name) {
            $breadcrumbs .= "<li>" . $name . "</li>";
        }

        return $breadcrumbs;
    }

    public static function getParts($cats, $id) {
        if (!$id) return false;

        $breadcrumbs = [];

        foreach ($cats as $k => $v) {
            if (isset($cats[$id])) {
                $breadcrumbs[$cats[$id]['alias']] = $cats[$id]['title'];
                $id = $cats[$id]['parent_id'];
            } else break;
        }

        return array_reverse($breadcrumbs);
    }
}