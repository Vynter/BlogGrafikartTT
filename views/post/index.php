<?php

use App\Connection;
use App\Helpers\Text;
use App\Model\Post;
use App\PaginatedQuery;
use App\URL;

$title = 'blog';
$pdo = Connection::getPDO();

$paginatedQuery = new PaginatedQuery(
    "SELECT * FROM post ORDER BY created_at DESC",
    'SELECT count(id) FROM post LIMIT 1'
);
/*

$currentPage = URL::getPositiveInt('page', 1); // (int) $page; // forcer $currentPage = (int)($_GET['page'] ?? 1) ?: 1;


$count = (int)$pdo->query('SELECT count(id) FROM post LIMIT 1')->fetch(PDO::FETCH_NUM)[0];
$perPage = 12;
$pages =  ceil($count / $perPage,); //arondi au chiffre supérieur 
if ($currentPage > $pages) {
    throw new Exception('Cette page n\éxiste pas ');
}
$offset = $perPage * ($currentPage - 1);
$query = $pdo->query("SELECT * FROM post ORDER BY created_at DESC LIMIT $perPage OFFSET $offset");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);*/
$posts = $paginatedQuery->getItems(Post::class);
$link = $router->url('home')

?>

<h1>Mon Blog</h1>
<div class="row">
    <?php foreach ($posts as $post) : ?>
    <div class="col-md-3">

        <?php require('card.php')
            ?>
    </div>
    <?php endforeach ?>
</div>


<div class="d-flex justify-content-between my-4">
    <?= $paginatedQuery->previousLink($link) ?>
    <?= $paginatedQuery->nextLink($link) ?>
</div>

















<!---
/*
$page = $_GET['page'] ?? 1;

if (!filter_var($page, FILTER_VALIDATE_INT)) { // c pour vérifier que c bien un int
    throw new Exception("numéro de page invalide"); URL::getINt
}
////////////////////////////
if ($page === "1") {
    header('Location: ' . $router->url('home'));
    http_response_code(301); // code de status c pour lui dire que ca été redirigé de maniére permanent
    exit();
} cette partie est transferer a public/index
*/
/*if ($currentPage <= 0) {
    throw new Exception('Numéro de page invalide'); remplacé par URL::getPositiveInt
}*/--->