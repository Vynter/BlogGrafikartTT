<?php

namespace App;

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
    private $count;

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
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();

        if ($currentPage > $pages) {
            throw new Exception('Cette page n\éxiste pas ');
        }
        $offset = $this->perPage * ($currentPage - 1);

        return  $this->pdo->query(
            $this->query .
                " LIMIT {$this->perPage} OFFSET $offset"
        )->fetchAll(PDO::FETCH_CLASS, $this->classMapping);
    }

    public function previousLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        if ($currentPage <= 1) return null;
        if ($currentPage > 2) $link .= "?page=" . ($currentPage - 1);
        return <<<HTML
        <a href="{$link}" class="btn btn-primary">&laquo; Page Précédent</a>
HTML;
    }

    public function nextLink(string $link): ?string
    {
        $currentPage = $this->getCurrentPage();
        $pages = $this->getPages();
        if ($currentPage >= $pages) return null;
        $link .= "?page=" . ($currentPage + 1);
        return <<<HTML
    <a href="{$link}" class="btn btn-primary ml-auto">Page Suivante &raquo;</a>
HTML;
    }

    private function getCurrentPage(): int
    {
        return URL::getPositiveInt('page', 1);
    }
    private function getPages(): int
    {
        if ($this->count === null) {
            $this->count = (int) $this->pdo
                ->query($this->queryCount)
                ->fetch(PDO::FETCH_NUM)[0];
        }

        return  ceil($this->count / $this->perPage);
    }
}