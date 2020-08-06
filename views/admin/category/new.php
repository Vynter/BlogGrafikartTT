<?php

use App\Auth;
use App\HTML\Form;
use App\Connection;
use App\Model\Category;
use App\ObjectHelper;
use App\Table\CategoryTable;
use Valitron\Validator;
use App\Validators\CategoryValidator;


Auth::check();
$success = false;
$errors = [];
$item = new Category(); // nouveau poste vide


if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $table = new CategoryTable($pdo);
    Validator::lang('fr'); //changement de langue
    $v = new CategoryValidator($_POST, $table, $item->getID());

    ObjectHelper::hydrate($item, $_POST, ['name', 'slug']);
    if ($v->validate()) {
        $table->create($item);
        header('Location: ' . $router->url('admin_categories') . '?created=1');
        exit();
    } else {
        $errors = $v->errors();
    }
}
$form = new Form($item, $errors);
?>

<?php if (!empty($errors)) : ?>
<div class="alert alert-danger">
    Catégorie n'a pas pu etre enregistrer, merci de corriger vos erreurs.
</div>
<?php endif ?>

<h1>Crée un la catégorie </h1>



<?php require('_form.php'); ?>