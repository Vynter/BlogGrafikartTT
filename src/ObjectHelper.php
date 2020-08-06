<?php

namespace App;

class ObjectHelper
{   //ObjectHelper::hydrate($item, $_POST, $fields);
    public static function hydrate($object, array $data, array $fields): void
    {
        foreach ($fields as $field) {
            $method = 'set' . ucfirst($field);  //ucfirst=1er carac en maj ceci va permettre d'avoir getSlug ou getName
            $object->$method($data[$field]);
        }
        /*$object
            ->setName($data['name'])
            ->setContent($_POST['content'])
            ->setSlug($_POST['slug'])
            ->setCreated_at($_POST['created_at']);*/
    }
}