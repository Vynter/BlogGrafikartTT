<?php

namespace App\Table;

use PDO;
use App\Table\Exception\NotFoundException;
use Exception;

abstract class Table
{
    protected $pdo;
    protected $table = null;
    protected $class = null;

    public function __construct(PDO $pdo)
    {
        if ($this->table === null) {
            throw new Exception("La class" . get_class($this) . " n'a pas de propriété table");
        }
        $this->pdo = $pdo;
    }
    public function find(int $id)
    {
        $query = $this->pdo->prepare(
            'SELECT * 
            FROM ' . $this->table . '
            WHERE id=:id
            '
        );
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, $this->class);
        $result = $query->fetch();
        if ($result === false) {
            throw new NotFoundException($this->table, $id);
        }
        return $result;
    }
    /**
     * Vérifie si une valeur existe dans la table
     */
    public function exists(string $field, $value): bool
    {
        $query = $this->pdo->prepare("SELECT COUNT(id) FROM {$this->table} WHERE $field = ?");
        $query->execute([$value]);
        return (int) $query->fetch(PDO::FETCH_NUM)[0] > 0;
    }
}