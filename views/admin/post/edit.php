<?php

use App\Connection;
use App\Table\PostTable;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
$success = false;
$errors = [];

if (!empty($_POST)) {
    if (empty($_POST['name'])) {
        $errors['name'][] = 'le champ titre ne peut pas étre vide';
    }
    if (mb_strlen($_POST['name']) <= 3) {
        $errors['name'][] = 'le champs titre doit contenir plus de 3 caractères';
    }

    if (empty($errors)) {
        $post->setName($_POST['name']);
        $postTable->update($post);
        $success = true;
    }
}

?>
<?php if ($success) : ?>
<div class="alert alert-success">
    L'article a bien été modifier
</div>
<?php endif ?>
<?php if (!empty($errors)) : ?>
<div class="alert alert-danger">
    <?= //implode(', ', $errors); 
            'ceci ne marche pas'; ?>
</div>
<?php endif ?>

<h1>Editer l'article <?= e($post->getName()) ?></h1>


<form action="" method="POST">
    <div class="form-group">
        <label for="name">Titre</label>
        <input type="text" class="form-control" name="name" value="<?= e($post->getName()) ?>">
    </div>
    <button class="btn btn-primary">Modifier</button>
</form>