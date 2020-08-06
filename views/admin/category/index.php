<?php

use App\Auth;
use App\Connection;
use App\Table\CategoryTable;

Auth::check();

$title = "Gestion des catégories";
$pdo = Connection::getPDO();
[$posts, $pagination] = (new CategoryTable($pdo))->findPaginated();
$link = $router->url('admin_categories');
?>

<?php if (isset($_GET['delete'])) : ?>
<div class="alert alert-success">L'article a bien été supprimer</div>

<?php endif ?>

<table class="table table-striped">
    <thead>
        <th>#</th>
        <th>Titre</th>
        <th><a href="<?= $router->url('admin_post_new') ?>" class="btn btn-primary">Nouveau</a> </th>
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
                <form action="<?= $router->url('admin_post_delete', ['id' => $post->getID()]) ?>" method="POST"
                    style="display:inline" onsubmit="return confirm('Voulez vous vraiment effectuer cette action')">

                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>



<div class="d-flex justify-content-between my-4">
    <?= $pagination->previousLink($link) ?>
    <?= $pagination->nextLink($link) ?>
</div>