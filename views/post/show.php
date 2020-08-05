<?php

use App\Connection;
use App\Model\Category;
use App\Model\Post;
use App\Table\CategoryTable;
use App\Table\PostTable;

//astuce pour regroupé le use de category et post
// use App\Model\{Category,Post};

$id = (int)($params)['id'];
$slug = ($params)['slug'];

$pdo = Connection::getPDO();

$post = (new PostTable($pdo))->find($id);
/* $query = $pdo->prepare('SELECT * FROM post WHERE id = :id');
$query->execute(['id' => $id]);
$query->setFetchMode(PDO::FETCH_CLASS, Post::class);
$post = $query->fetch(); 
 vérification que l'utilisateur n'a pas touché a l'id 
if ($post === false) {
    throw new Exception('aucun article ne corespend à ID que vous chercher');
}*/
if ($post->getSlug() !== $slug) {
    $url = $router->url('post', ['id' => $post->getID(), 'slug' => $post->getSlug()]);
    http_response_code(301); // a revoir
    header('Location: ' . $url);
}

(new CategoryTable($pdo))->hydratePosts([$post]);
/* 
$query = $pdo->prepare(
    'SELECT c.id,c.slug,c.name 
    FROM post_category pc join category c on pc.category_id=c.id 
    WHERE post_id= :id'
);
$query->execute(['id' => $post->getID()]);
$categories = $query->fetchAll(PDO::FETCH_CLASS, Category::class);
 */
?>


<h1 class="card-title"><?= e($post->getName()) ?></h1>
<p class="text-muted"><?= $post->getCreated_at()->format('d F Y') ?></p>
<?php foreach ($post->getCategories() as $k => $category) : ?>
<?php if ($k > 0) : ?><?php echo ', '; ?><?php endif ?>
<a
    href="<?= $router->url('category', ['id' => $category->getID(), 'slug' => $category->getSlug()]); ?>"><?= e($category->getName()) ?></a>
<?php endforeach ?>
<p><?= $post->getFormattedContent(); ?></p>
<p>
    <a href="<?= $router->url('home') ?>" class="btn btn-primary">Retour</a>
</p>