<?php

use App\Connection;
use App\Table\PostTable;


$title = 'blog';
$pdo = Connection::getPDO();
$table = new PostTable($pdo);
list($posts, $pagination) = $table->findPaginated();



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
    <?= $pagination->previousLink($link) ?>
    <?= $pagination->nextLink($link) ?>
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