<?php

namespace App;

use AltoRouter;

class Router
{
    private $viewPath;
    /**
     * @var altoRouter
     */
    private $router;

    public function __construct($viewPath)
    {
        $this->viewPath = $viewPath;
        $this->router = new AltoRouter();
    }

    /**
     * get
     *
     * @param  mixed $url ce qu'on va écrire
     * @param  mixed $view complément du lien
     * @param  mixed $name le nom
     * @return self
     */
    public function get(string $url, string  $view, $name = null): self
    {
        $this->router->map('GET', $url, $view, $name);
        return $this;
    }

    public function post(string $url, string  $view, $name = null): self
    {
        $this->router->map('POST', $url, $view, $name);
        return $this;
    }

    public function match(string $url, string  $view, $name = null): self
    {
        $this->router->map('POST|GET', $url, $view, $name);
        return $this;
    }

    public function url($name, $params = [])
    {
        return $this->router->generate($name, $params);
    }

    public function run(): self
    {
        $match = $this->router->match();
        $view = $match['target'];
        $params = $match['params'];
        $router = $this; // utilisé dans generate
        ob_start();
        require $this->viewPath . DIRECTORY_SEPARATOR . $view . '.php';
        $content = ob_get_clean();
        require $this->viewPath . DIRECTORY_SEPARATOR . 'layouts/default.php';
        return $this;
    }
}