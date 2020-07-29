<?php

use App\Connection;
use Valitron\Validator;
use App\Table\PostTable;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
$success = false;
$errors = [];

if (!empty($_POST)) {
    Validator::lang('fr'); //changement de langue
    $v = new Validator($_POST);
    $v->labels(array( //on change le nom des label pour les erreurs
        'name' => 'Titre',
        'content' => 'Contenu'
    ));
    $v->rule('required', 'name'); // définition des régles
    $v->rule('lengthBetween', 'name', 3, 200); // la longueur doit étre entre 3 a 200 caract
    /*
    if (empty($_POST['name'])) {
        $errors['name'][] = 'le champ titre ne peut pas étre vide';
    }
    if (mb_strlen($_POST['name']) <= 3) {
        $errors['name'][] = 'le champs titre doit contenir plus de 3 caractères';
    }*/
    $post->setName($_POST['name']);
    if ($v->validate()) {
        $postTable->update($post);
        $success = true;
    } else {
        $errors = $v->errors();
    }
    /*if (empty($errors)) {
        $post->setName($_POST['name']);
        $postTable->update($post);
        $success = true;
    }*/
}

?>
<?php if ($success) : ?>
<div class="alert alert-success">
    L'article a bien été modifier
</div>
<?php endif ?>
<?php if (!empty($errors)) : ?>
<div class="alert alert-danger">
    Article n'a pas pu etre modifié, merci de corriger vos erreurs
</div>
<?php endif ?>

<h1>Editer l'article <?= e($post->getName()) ?></h1>


<form action="" method="POST">
    <div class="form-group">
        <label for="name">Titre</label>
        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" name="name"
            value="<?= e($post->getName()) ?>">
        <div class="invalid-feedback">
            <?= isset($errors['name']) ? implode('<br>', $errors['name']) : "" ?>
        </div>
    </div>
    <button class="btn btn-primary">Modifier</button>
</form>