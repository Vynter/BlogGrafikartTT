<?php

use App\Connection;
use App\HTML\Form;
use Valitron\Validator;
use App\Table\PostTable;
use App\Validators\PostValidator;

$pdo = Connection::getPDO();
$postTable = new PostTable($pdo);
$post = $postTable->find($params['id']);
$success = false;
$errors = [];

if (!empty($_POST)) {
    Validator::lang('fr'); //changement de langue
    $v = new PostValidator($_POST, $postTable, $post->getID());
    /*    $v = new Validator($_POST);
    $v->labels(array( //on change le nom des label pour les erreurs
        'name' => 'Titre',
        'content' => 'Contenu'
    ));
    $v->rule('required', 'name'); // définition des régles
    $v->rule('required', 'slug');
    /*pour regrouper les deux 
    $v->rule('required', ['name','slug']);*/
    /*$v->rule('lengthBetween', 'name', 3, 200); // la longueur doit étre entre 3 a 200 caract
    $v->rule('lengthBetween', 'slug', 3, 200);*/
    $post
        ->setName($_POST['name'])
        ->setContent($_POST['content'])
        ->setSlug($_POST['slug'])
        ->setCreated_at($_POST['created_at']);

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
$form = new Form($post, $errors);
?>
<?php if ($success) : ?>
<div class="alert alert-success">
    L'article a bien été modifier.
</div>
<?php endif ?>
<?php if (!empty($errors)) : ?>
<div class="alert alert-danger">
    Article n'a pas pu etre modifié, merci de corriger vos erreurs.
</div>
<?php endif ?>

<h1>Editer l'article <?= e($post->getName()) ?></h1>



<form action="" method="POST">
    <?= $form->input('name', 'Titre') ?>
    <?= $form->input('slug', 'URL') ?>
    <?= $form->textarea('content', 'Contenu') ?>
    <?= $form->input('created_at', 'Date de création') ?>


    <button class="btn btn-primary">Modifier</button>
</form>