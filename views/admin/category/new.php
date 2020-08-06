<?php

use App\Auth;
use App\HTML\Form;
use App\Connection;
use App\Model\Post;
use App\ObjectHelper;
use Valitron\Validator;
use App\Table\PostTable;
use App\Validators\PostValidator;

Auth::check();
$success = false;
$errors = [];
$post = new Post(); // nouveau poste vide
$post->setCreated_at(date('Y-m-d H:i:s'));

if (!empty($_POST)) {
    $pdo = Connection::getPDO();
    $postTable = new PostTable($pdo);
    Validator::lang('fr'); //changement de langue
    $v = new PostValidator($_POST, $postTable, $post->getID());

    ObjectHelper::hydrate($post, $_POST, ['name', 'content', 'slug', 'created_at']);
    if ($v->validate()) {
        $postTable->create($post);
        header('Location: ' . $router->url('admin_post', ['id' => $post->getID()]) . '?created=1');
        exit();
    } else {
        $errors = $v->errors();
    }
}
$form = new Form($post, $errors);
?>

<?php if (!empty($errors)) : ?>
<div class="alert alert-danger">
    Article n'a pas pu etre enregistrer, merci de corriger vos erreurs.
</div>
<?php endif ?>

<h1>Crée un l'article </h1>



<?php require('_form.php'); ?>