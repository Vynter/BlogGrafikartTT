<?php

namespace App\Validators;

use App\Table\PostTable;
use Valitron\Validator;

abstract class AbstractValidator
{
    protected $data; // vu qu'on utilise une class mére on garde les deux en protégé au lieu privé
    protected $validator;

    public function __construct(array $data)
    {
        $this->data = $data;
        $this->validator = new Validator($data);
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