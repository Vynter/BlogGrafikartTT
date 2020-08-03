<?php

namespace App\Validators;

use App\Table\PostTable;
use Valitron\Validator;

class PostValidator
{
    private $data;
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
        }, 'slug', 'déja utilisé');
        $this->validator = $v;
    }
    public function validate(): bool
    {
        return $this->validator->validate();
    }
    public function errors(): array
    {
        return $this->validator->errors();
    }
}