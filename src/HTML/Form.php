<?php

namespace App\HTML;

class Form
{
    private $data;
    private $errors;

    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }
    public function input(string $nom, string $label): string
    {
        return '';
    }
    public function textarea(string $nom, string $label): string
    {
        return '';
    }
    /*
       <div class="form-group">
        <label for="name">Titre</label>
        <input type="text" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>" name="name"
value="<?= e($post->getName()) ?>">
<div class="invalid-feedback">
    <?= isset($errors['name']) ? implode('<br>', $errors['name']) : "" ?>
</div>
</div>
*/
}