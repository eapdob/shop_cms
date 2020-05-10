<?php

namespace shop\base;

class View {

    public $route;
    public $controller;
    public $model;
    public $view;
    public $prefix;
    public $layout;
    public $data = [];
    public $meta = [];

    public function __construct($route, $layout = '', $view = '', $meta = '') {
        $this->route = $route;
        $this->prefix = str_replace('\\', DIRECTORY_SEPARATOR, $route['prefix']);
        $this->controller = $route['controller'];
        $this->model = $route['controller'];
        $this->view = $view;
        $this->meta = $meta;

        if ($layout === false) {
            $this->layout = false;
        } else {
            $this->layout = $layout ? $layout : LAYOUT;
        }
    }

    public function render($data) {
        if (is_array($data)) extract($data);

        $viewFile = APP . "/views/{$this->prefix}" . lcfirst($this->controller) . "/{$this->view}.php";

        if (is_file($viewFile)) {
            ob_start();
            require_once $viewFile;
            $content = ob_get_clean();
        } else {
            throw new \Exception("Страница не найдена {$viewFile}", 500);
        }

        if ($this->layout !== false) {
            $layoutFile = APP . "/views/layouts/{$this->layout}.php";

            if (is_file($layoutFile)) {
                require_once $layoutFile;
            } else {
                throw new \Exception("Файл шаблона не найден {$viewFile}", 500);
            }
        } else {
            throw new \Exception("Шаблон не найден {$viewFile}", 500);
        }
    }

    public function getMeta() {
        $output = '<title>' . $this->meta["title"] . '</title>' . PHP_EOL;
        $output .= '<meta name="description" content="' . $this->meta['desc'] . '">' . PHP_EOL;
        $output .= '<meta name="keywords" content="' . $this->meta['keywords'] . '">' . PHP_EOL;
        return $output;
    }
}