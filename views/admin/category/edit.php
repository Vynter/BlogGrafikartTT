<?php

use App\Auth;
use App\HTML\Form;
use App\Connection;
use App\ObjectHelper;
use Valitron\Validator;
use App\Table\CategoryTable;
use App\Validators\CategoryValidator;

Auth::check();
$pdo = Connection::getPDO();
$table = new CategoryTable($pdo);
$item = $table->find($params['id']);
$success = false;
$errors = [];
$fields = ['name', 'slug'];
if (!empty($_POST)) {
    Validator::lang('fr'); //changement de langue
    $v = new CategoryValidator($_POST, $table, $item->getID());
    ObjectHelper::hydrate($item, $_POST, $fields);
    if ($v->validate()) {
        $table->update($item);
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
$form = new Form($item, $errors);
?>
<?php if ($success) : ?>
<div class="alert alert-success">
    La catégorie a bien été modifier.
</div>
<?php endif ?>
<?php if (isset($_GET['created'])) : ?>
<div class="alert alert-success">
    La catégorie a bien été Crée.
</div>
<?php endif ?>
<?php if (!empty($errors)) : ?>
<div class="alert alert-danger">
    Catégorie n'a pas pu etre modifié, merci de corriger vos erreurs.
</div>
<?php endif ?>

<h1>Editer la catégorie <?= e($item->getName()) ?></h1>


<?php require('_form.php'); ?>