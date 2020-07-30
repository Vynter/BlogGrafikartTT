<?php

namespace App;

use Valitron\Validator;

class ValidatorByMe extends Validator
{

    protected function checkAndSetLabel($field, $message, $params)
    {

        return  str_replace('{field}', '', $message);
    }
}