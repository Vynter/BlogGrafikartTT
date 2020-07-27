<?php
// ma création //
use App\URL;
use App\Connection;
use App\Model\Category;
use App\Model\Post;
use App\Router;
use App\PaginatedQuery;
use App\Table\CategoryTable;
use App\Table\PostTable;

$id = (int)($params)['id'];
$slug = ($params)['slug'];

$pdo = Connection::getPDO();

$categoryTable = new CategoryTable($pdo);
$category = $categoryTable->find($id);




if ($category->getSlug() !== $slug) {
    $url = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]);
    http_response_code(301); // a revoir
    header('Location: ' . $url);
}

$title = "Catégorie {$category->getName()}";

[$posts, $PaginatedQuery] = (new PostTable($pdo))->findPaginatedForCategory($category->getID());
/* same as 
$table = new PostTable($pdo);
list($posts, $pagination) = $table->findPaginatedForCategory($category->getID);
*/


$link = $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]); // lien actuel

?>

<h1><?= e($title) ?></h1>

<div class="row">
    <?php foreach ($posts as $post) : ?>
    <div class="col-md-3">

        <?php require dirname(__DIR__) . '/post/card.php';
            ?>
    </div>
    <?php endforeach ?>
</div>


<div class="d-flex justify-content-between my-4">
    <?= $PaginatedQuery->previousLink($link) ?>

    <?= $PaginatedQuery->nextLink($link) ?>


    <!--

// copy/past/classe $currentPage = URL::getPositiveInt('page', 1); // (int) $page; // forcer $currentPage = (int)($_GET['page'] ?? 1) ?: 1;


/*$count = (int)$pdo
    ->query('SELECT count(category_id) FROM post_category WHERE category_id= ' . $category->getID())
    ->fetch(PDO::FETCH_NUM)[0];*/
//  copy/past/classe $perPage = 12;
//  copy/past/classe $pages =  ceil($count / $perPage,); //arondi au chiffre supérieur 
/*  copy/past/classe if ($currentPage > $pages) {
    throw new Exception('Cette page n\éxiste pas ');

$offset = $perPage * ($currentPage - 1);}
$query = $pdo->query("
    SELECT p.* 
    FROM post p 
    JOIN post_category pc ON pc.post_id = p.id 
    WHERE pc.category_id = {$category->getID()}
    ORDER BY created_at DESC 
    LIMIT $perPage OFFSET $offset
    ");
$posts = $query->fetchAll(PDO::FETCH_CLASS, Post::class);*/-->