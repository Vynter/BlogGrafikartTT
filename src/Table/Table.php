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
     * aprés avoir modifier un article ca nous permet d'enregistré l'ancienne version sans avoir une erreur de valeur existe déja dans la bdd
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

    /**
     * @return ceci est le paginatedquery qu'on va utiliser dans admin category
     */
    public function all(): array
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
        /*$query=$this->pdo->query($sql);
    $query->setFetchMode(PDO::FETCH_CLASS, $this->class);*/
    }
}