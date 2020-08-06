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

    public function deleteParent(int $id) // méthode non utilisé
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->table}");
        }
    }

    public function updateParent($data, $id): void // méthode non utilisé
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key =:$key";
        }
        $query = $this->pdo->prepare("UPDATE {$this->table} SET " . implode(", ", $fields) . " WHERE id = $id");
        $ok = $query->execute(array_merge($data, ["id" => $id]));
        if ($ok === false) {
            throw new Exception("Impossible de supprimer l'enregistrement {$data->getID()} dans la table {$this->table}");
        }
    }

    public function createParent($data): int //méthode non utilisé
    {
        $fields = [];
        foreach ($data as $key => $value) {
            $fields[] = "$key =:$key";
        }
        $query = $this->pdo->prepare("INSERT INTO {$this->table} set " . implode(", ", $fields));
        $ok = $query->execute([$data]);
        if ($ok === false) {
            throw new Exception("Impossible de crée l'enregistrement {$data->getID()} dans la table {$this->table}");
        }
        return (int)$this->pdo->lastInsertId();
    }
}