<?php

use App\Auth;
use App\Connection;
use App\Table\CategoryTable;

Auth::check();

$title = "Gestion des catégories";
$pdo = Connection::getPDO();
$items = (new CategoryTable($pdo))->all();
$link = $router->url('admin_categories');
?>

<?php if (isset($_GET['delete'])) : ?>
<div class="alert alert-success">L'article a bien été supprimer</div>

<?php endif ?>

<table class="table table-striped">
    <thead>
        <th>#</th>
        <th>Titre</th>
        <th>URL</th>
        <th><a href="<?= $router->url('admin_categories_new') ?>" class="btn btn-primary">Nouveau</a> </th>
    </thead>
    <tbody>
        <?php foreach ($items as $item) : ?>
        <tr>
            <td>
                <?= $item->getID() ?>
            </td>
            <td>
                <a href="<?= $router->url('admin_category', ['id' => $item->getID()]) ?>">
                    <?= e($item->getName()) ?>
                </a>
            </td>
            <td>
                <?= $item->getSlug() ?>
            </td>
            <td>
                <a href="<?= $router->url('admin_category', ['id' => $item->getID()]) ?>" class="btn btn-primary">
                    Editer
                </a>
                <form action="<?= $router->url('admin_categories_delete', ['id' => $item->getID()]) ?>" method="POST"
                    style="display:inline" onsubmit="return confirm('Voulez vous vraiment effectuer cette action')">

                    <button type="submit" class="btn btn-danger">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach ?>
    </tbody>
</table>



<div class="d-flex justify-content-between my-4">
    <?= "" //$pagination->previousLink($link) 
    ?>
    <?= "" // $pagination->nextLink($link) 
    ?>
</div>