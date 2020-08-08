<?php

namespace App\HTML;

use DateTime;
use DateTimeInterface;

class Form
{
    private $data;
    private $errors;

    public function __construct($data, array $errors)
    {
        $this->data = $data;
        $this->errors = $errors;
    }
    public function input(string $key, string $label): string
    {

        $type = $key === "password" ? "password" : "text";
        $value = $this->getValue($key);
        /*gestion de la partie invalide*/
        $inputClass = 'form-control';
        $invalidFeedback = "";
        if (isset($this->errors[$key])) {
            $inputClass .= ' is-invalid';

            $invalidFeedback = '<div class="invalid-feedback">' . implode('<br>', $this->errors[$key]) . '</div>';
        }
        return <<<HTML
         <div class="form-group">
        <label for="field{$key}">{$label}</label>
        <input type="{$type}" id ="field{$key}" class="{$inputClass}" name="{$key}" value="{$value}" required>
        {$invalidFeedback}
        </div>
HTML;
    }

    public function select(string $key, string $label, array $options = []): string
    {
        $value = $this->getValue($key);
        $optionsHTML = [];
        foreach ($options as $k => $v) {
            $selected = in_array($k, $value) ? ' selected' : "";
            $optionsHTML[] = "<option value='$k' $selected>$v</option>";
        }

        $optionsHTML = implode('', $optionsHTML);

        /*gestion de la partie invalide*/
        $inputClass = 'form-control';
        $invalidFeedback = "";
        if (isset($this->errors[$key])) {
            $inputClass .= ' is-invalid';
            $invalidFeedback = '<div class="invalid-feedback">' . implode('<br>', $this->errors[$key]) . '</div>';
        }

        return <<<HTML
         <div class="form-group">
        <label for="field{$key}">{$label}</label>
        <Select id ="field{$key}" multiple class="{$inputClass}" name="{$key}[]" required>{$optionsHTML}</select> <!--//name="{$key}[] est important pour choisir plusieur champs a la fois-->
        {$invalidFeedback}
        </div>
HTML;
    }
    /*
  </div>
  <div class="form-group">
    <label for="exampleFormControlSelect2">Example multiple select</label>
    <select multiple class="form-control" id="exampleFormControlSelect2">
      <option>1</option>
      <option>2</option>
      <option>3</option>
      <option>4</option>
      <option>5</option>
    </select>
  </div>*/
    public function textarea(string $key, string $label): string
    {
        $value = $this->getValue($key);
        /*gestion de la partie invalide*/
        $inputClass = 'form-control';
        $invalidFeedback = "";
        if (isset($this->errors[$key])) {
            $inputClass .= ' is-invalid';
            $invalidFeedback = '<div class="invalid-feedback">' . implode('<br>', $this->errors[$key]) . '</div>';
        }
        return <<<HTML
         <div class="form-group">
        <label for="field{$key}">{$label}</label>
        <textarea type="text" id ="field{$key}" class="{$inputClass}" name="{$key}" required>{$value}</textarea>
        {$invalidFeedback}
        </div>
HTML;
    }

    private function getValue(string $key)
    {
        if (is_array($this->data)) {
            return $this->data[$key] ?? null;
        }
        $method = 'get' . ucfirst($key);  //ucfirst=1er carac en maj ceci va permettre d'avoir getSlug ou getName
        $value = $this->data->$method();
        if ($value instanceof DateTimeInterface) { // le cas de getCreated_at
            return $value->format('Y-m-d H:i:s');
        }
        return $value;
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