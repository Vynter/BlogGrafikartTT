<?php

use App\Router;
use Whoops\Run;
use Whoops\Handler\Handler;
use Whoops\Handler\PrettyPageHandler;


require '../vendor/autoload.php';

define('DEBUG_TIME', microtime(true));
// to be commented in deployement
$whoops = new \Whoops\Run;
$whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
$whoops->register();
// until here

/* Ceci permet de construire un lien sans le $_GET["PAGE"] */
if (isset($_GET['page']) && $_GET['page'] === '1') {
    $uri = $_SERVER['REQUEST_URI'];
    $uri = explode("?", $uri)[0];
    $get = $_GET;
    unset($get['page']);
    $query = http_build_query($get);

    if (!empty($query)) {
        $uri = $uri . '?' . $query;
    }
    http_response_code(301);
    header('Location: ' . $uri);
    exit();
}
/*
if ($page === "1") {
    header('Location: ' . $router->url('home'));
    http_response_code(301); // code de status c pour lui dire que ca été redirigé de maniére permanent
    exit();
}*/

$router = new Router(dirname(__DIR__) . '/views');
$router
    ->get('/', 'post/index', 'home')
    ->get('/blog/categoty/[*:slug]-[i:id]', 'category/show', 'category') // quand deux lien se ressemble de préférence mettre la plus compliqué en premier
    ->get('/blog/[*:slug]-[i:id]', 'post/show', 'post')
    ->get('/admin', 'admin/post/index', 'admin_posts')
    ->match('/admin/posts/[i:id]', 'admin/post/edit', 'admin_post') // quand on choisir ART a edit on utilise get et post when we valid edit
    ->post('/admin/posts/[i:id]/delete', 'admin/post/delete', 'admin_post_delete')
    ->get('/admin/posts/new', 'admin/post/new', 'admin_post_new')
    ->run();












    /*
define('VIEW_PATH', dirname(__DIR__) . '/views');
 Ancienne version
$router = new AltoRouter();
$router->map('GET', '/blog', function () {
    require VIEW_PATH . '/post/index.php';
});
$router->map('GET', '/blog/categoty', function () {
    require VIEW_PATH . '/category/show.php';
});
$match = $router->match();
$match['target']();
Nouvelle version   */