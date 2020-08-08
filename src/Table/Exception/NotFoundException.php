<?php

namespace App\Table\Exception;

use Exception;

class NotFoundException extends Exception
{
    public function __construct(string $table, $id)
    {
        $this->message = "aucun enregisyrement ne correspend à ID #$id dans la table '$table' que vous chercher";
    }
}