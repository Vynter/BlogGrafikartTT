<?php

namespace App\Validators;

use App\Table\CategoryTable;
use Valitron\Validator;

class CategoryValidator extends AbstractValidator
{

    public function __construct(array $data, CategoryTable $table, ?int $id = null)
    {
        parent::__construct($data); // on appel le constructeur parent
        $this->validator->labels(array( //on change le nom des label pour les erreurs
            'name' => 'Titre',
            'content' => 'Contenu'
        ));
        $this->validator->rule('required', ['name', 'slug']); // définition des régles
        $this->validator->rule('lengthBetween', ['name', 'slug'], 3, 200); // la longueur doit étre entre 3 a 200 caract
        $this->validator->rule('slug', 'slug'); //la régle slug(utilisé que les carac int et _) appliqué sur le champ slug
        $this->validator->rule(function ($field, $value) use ($table, $id) {
            return !$table->exists($field, $value, $id);
        }, ['slug', 'name'], ' est déja utilisé');
    }


    /*private $data;
    private $validator;

    public function __construct(array $data, PostTable $table, ?int $postID = null)
    {
        $this->data = $data;
        $v = new Validator($data);
        $v->labels(array( //on change le nom des label pour les erreurs
            'name' => 'Titre',
            'content' => 'Contenu'
        ));
        $v->rule('required', ['name', 'slug']); // définition des régles
        $v->rule('lengthBetween', ['name', 'slug'], 3, 200); // la longueur doit étre entre 3 a 200 caract
        $v->rule('slug', 'slug'); //la régle slug(utilisé que les carac int et _) appliqué sur le champ slug
        $v->rule(function ($field, $value) use ($table, $postID) {
            return !$table->exists($field, $value, $postID);
        }, ['slug', 'name'], ' est déja utilisé');
        $this->validator = $v;
    }
    public function validate(): bool
    {
        return $this->validator->validate();
    }
    public function errors(): array
    {
        return $this->validator->errors();
    }*/
}