<?php

use App\Connection;
use App\HTML\Form;
use App\Model\User;
use App\Table\Exception\NotFoundException;
use App\Table\UserTable;

$user = new User();
$errors = [];

if (!empty($_POST)) {
    $user->setUsername($_POST['username']);
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $errors['password'] = ["Identifiant ou mot de passe incorrect"];
    }
    $pdo = Connection::getPDO();
    $table = new UserTable($pdo);

    try {
        $u = $table->findByUsername($_POST['username']);
        if (password_verify($_POST['password'], $u->GetPassword()) === false) {
            $errors['password'] = ["mdp incorrect"];;
        } else {
            session_start();
            $_SESSION['auth'] = $u->getId();
            header('Location: ' . $router->url('admin_posts'));
            exit();
        }
    } catch (NotFoundException $e) {
        $errors['password'] = ["Identifiant ou mot de passe incorrect"];;
    }
}



$form = new Form($user, $errors);
?>
<h1>Se connecter</h1>
<?php if (isset($_GET['forbidden'])) : ?>
<div class="alert alert-danger">
    Vous ne pouvais pas accéder a cette page
</div>
<?php endif ?>
<form action="<?= $router->url('login') ?>" method="POST">
    <?= $form->input('username', 'Nom d\'utilisateur'); ?>
    <?= $form->input('password', 'Mot de passe)'); ?>
    <button type="submit" class="btn btn-primary">Se connecter</button>

</form>