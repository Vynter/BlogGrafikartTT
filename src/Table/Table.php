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
     * @var except c pour nous permettre d'enregistré aprés avoir fait nimp quel modife
     */
    public function exists(string $field, $value, ?int $except = null): bool
    {
        $sql = "SELECT COUNT(id) FROM {$this->table} WHERE $field = ?";
        if ($except !== null) {
            $sql .= " AND id != {$except}";
        }
        $query = $this->pdo->prepare($sql);
        $query->execute([$value]);
        return (int) $query->fetch(PDO::FETCH_NUM)[0] > 0;
    }
}