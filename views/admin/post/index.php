<?php

use App\Connection;
use App\Table\PostTable;

$title = "Administration";
$pdo = Connection::getPDO();
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();
$link = $router->url('admin_posts');
?>

<?php if (isset($_GET['delete'])) : ?>
<div class="alert alert-success">L'article a bien été supprimer</div>

<?php endif ?>

<table class="table table-striped">
    <thead>
        <th>#</th>
        <th>Titre</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php foreach ($posts as $post) : ?>
        <tr>
            <td>
                <?= $post->getID() ?>
            </td>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getID()]) ?>">
                    <?= e($post->getName()) ?>
                </a>
            </td>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getID()]) ?>" class="btn btn-primary">
                    Editer
                </a>
                <a href="<?= $router->url('admin_post_delete', ['id' => $post->getID()]) ?>" class="btn btn-danger"
                    onclick="return confirm('Voulez vous vraiment effectuer cette action')">
                    Supprimer
                </a>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>



<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link) ?>
    <?= $pagination->nextLink($link) ?>
</div>