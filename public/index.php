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

/* Ceci permet de construire un lien sans le $_GET["PAGE"] sans le page=1*/
if (isset($_GET['page']) && $_GET['page'] === '1') {
    $uri = $_SERVER['REQUEST_URI']; // url complet
    $uri = explode("?", $uri)[0]; // "/"
    $get = $_GET; //"liste des argument page=2&zz=5
    unset($get['page']); // supression de get page
    $query = http_build_query($get); // reconstruction de l'url sans page

    if (!empty($query)) {
        $uri = $uri . '?' . $query; // render: /?zz=5 , pour rendre le reste des argument sans le page=1
    }
    http_response_code(301);
    header('Location: ' . $uri); // redirection è '/' or '/?zz=5'
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
    ->get('/', 'post/index', 'home') // liste des aricles
    ->get('/blog/categoty/[*:slug]-[i:id]', 'category/show', 'category') // quand deux lien se ressemble de préférence mettre la plus compliqué en premier
    ->get('/blog/[*:slug]-[i:id]', 'post/show', 'post') //contenu de l'article
    ->match('/login', 'auth/login', 'login')
    ->post('/logout', 'auth/logout', 'logout')
    //Admin
    //Gestion des articles
    ->get('/admin', 'admin/post/index', 'admin_posts') // index de l'administration
    ->match('/admin/posts/[i:id]', 'admin/post/edit', 'admin_post') // quand on choisir ART a edit on utilise get et post when we valid edit
    ->post('/admin/posts/[i:id]/delete', 'admin/post/delete', 'admin_post_delete') // article suprimer
    ->match('/admin/posts/new', 'admin/post/new', 'admin_post_new') // création de l'article
    //Gestion des catégories
    ->get('/admin/categories', 'admin/category/index', 'admin_categories') // index de l'administration
    ->match('/admin/category/[i:id]', 'admin/category/edit', 'admin_category') // quand on choisir ART a edit on utilise get et post when we valid edit
    ->post('/admin/category/[i:id]/delete', 'admin/category/delete', 'admin_categories_delete') // article suprimer
    ->match('/admin/category/new', 'admin/category/new', 'admin_categories_new') // création de l'article

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