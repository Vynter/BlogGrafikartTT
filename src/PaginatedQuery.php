<?php

namespace App\Model;

use PDO;
use App\URL;
use Exception;
use App\Connection;

class PaginatedQuery
{
    private $query;
    private $queryCount;
    private $classMapping;
    private $pdo;
    private $perPage;

    public function __construct(
        string $query,
        string $queryCount,
        string $classMapping,
        PDO $pdo = null,
        int $perPage = 12
    ) {
        $this->query = $query;
        $this->queryCount = $queryCount;
        $this->classMapping = $classMapping;
        $this->pdo = $pdo ?? Connection::getPDO();
        $this->perPage = $perPage;
    }
    public function getItems(): array
    {
        $currentPage = URL::getPositiveInt('page', 1);
        $count = (int) $this->pdo
            ->query($this->queryCount)
            ->fetch(PDO::FETCH_NUM)[0];
        $pages =  ceil($count / $this->perPage);
        if ($currentPage > $pages) {
            throw new Exception('Cette page n\éxiste pas ');
        }
        $offset = $this->perPage * ($currentPage - 1);

        return  $this->pdo->query(
            $this->query .
                " LIMIT {$this->perPage} OFFSET $offset"
        )->fetchAll(PDO::FETCH_CLASS, $this->classMapping);
    }
}