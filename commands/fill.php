<?php

use App\Connection;

require dirname(__DIR__) . '/vendor/autoload.php';

$faker = Faker\Factory::create('fr_FR');


$pdo = Connection::getPDO();

$pdo->exec('SET FOREIGN_KEY_CHECKS=0'); // on désactive les clé étrangéres pour que le script marche
$pdo->exec('TRUNCATE TABLE post_category');
$pdo->exec('TRUNCATE TABLE post');
$pdo->exec('TRUNCATE TABLE category');
$pdo->exec('TRUNCATE TABLE user');

$pdo->exec('SET FOREIGN_KEY_CHECKS=1'); // on réactive les clé étrangéres

$posts = [];
$categories = [];

for ($i = 0; $i < 50; $i++) {
    $pdo->exec("INSERT INTO post SET name= '{$faker->sentence()}', slug='{$faker->slug}',created_at='{$faker->date} {$faker->time}',content='{$faker->paragraphs(rand(3, 15), true)}'");
    $posts[] = $pdo->lastInsertId();
}

for ($i = 0; $i < 5; $i++) {
    $pdo->exec("INSERT INTO category SET name= '{$faker->sentence(3)}', slug='{$faker->slug}'");
    $categories[] = $pdo->lastInsertId();
}

/*il parcours tt les articles et il génére dans un tableau randomCategories (de 0 a 5 valeurs du tableau catégorie) 
et il insert chaque catégorie généré avec le poste pour crée une multitude de couple */
foreach ($posts as $post) {
    $randomCategories = $faker->randomElements($categories, rand(0, count($categories)));
    foreach ($randomCategories as $category) {
        $pdo->exec("INSERT INTO post_category SET post_id=$post , category_id=$category ");
    }
}

$password = password_hash('admin', PASSWORD_BCRYPT);
$pdo->exec("INSERT INTO user SET username='admin', password='$password'");