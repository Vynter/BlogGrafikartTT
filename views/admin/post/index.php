<?php

use App\Connection;
use App\Table\PostTable;

$title = "Administration";
$pdo = Connection::getPDO();
[$posts, $pagination] = (new PostTable($pdo))->findPaginated();
$link = $router->url('admin_posts');
?>

<table class="table table-striped">
    <thead>
        <th>Titre</th>
        <th>Actions</th>
    </thead>
    <tbody>
        <?php foreach ($posts as $post) : ?>
        <tr>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getID()]) ?>">
                    <?= e($post->getName()) ?>
                </a>
            </td>
            <td>
                <a href="<?= $router->url('admin_post', ['id' => $post->getID()]) ?>">
                    Editer
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