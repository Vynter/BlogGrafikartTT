<?php

namespace App\Table;

use PDO;
use Exception;
use App\Model\Category;
use App\Table\Exception\NotFoundException;

class CategoryTable extends Table
{
    protected $table = "category";
    protected $class = Category::class;
    /*
    public function find(int $id): Category
    {
        $query = $this->pdo->prepare(
            'SELECT * 
            FROM category
            WHERE id=:id
            '
        );
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, Category::class);
        $result = $query->fetch();
        if ($result === false) {
            throw new NotFoundException('category', $id);
        }
        return $result;
    }*/
    /**
     * @param app\Model\Post[] $posts
     */
    public function hydratePosts(array $posts): void
    {
        $postsByID = [];
        foreach ($posts as $post) {
            $postsByID[$post->getID()] = $post;
        }

        /**Pour afficher les catégories de chaque art par page (requéte opti) */
        $categories = $this->pdo
            ->query('SELECT c.* , pc.post_id
                    FROM post_category pc
                    JOIN category c ON c.id = pc.category_id
                    WHERE pc.post_id IN (' . implode(',', array_keys($postsByID)) . ')        
            ')->fetchAll(PDO::FETCH_CLASS, Category::class);

        foreach ($categories as $category) {
            //$postsByID[$category->getPostID()]->categories[] = $category;
            $postsByID[$category->getPostID()]->addCategory($category);
        }
    }

    //////////
    public function create(Category $category): void
    {
        $query = $this->pdo->prepare("INSERT INTO {$this->table} set  name= :name,slug =:slug");
        $ok = $query->execute([
            'name' => $category->getName(),
            'slug' => $category->getSlug(),
        ]);
        if ($ok === false) {
            throw new Exception("Impossible de crée l'enregistrement {$category->getID()} dans la table {$this->table}");
        }
        $category->setID($this->pdo->lastInsertId());
    }
    public function update(Category $category): void
    {
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name= :name,slug =:slug WHERE id = :id");
        $ok = $query->execute([
            'id' => $category->getID(),
            'name' => $category->getName(),
            'slug' => $category->getSlug()
        ]);
        if ($ok === false) {
            throw new Exception("Impossible de supprimer l'enregistrement {$category->getID()} dans la table {$this->table}");
        }
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->table}");
        }
    }

    public function list(): array
    {
        $sql = "SELECT * FROM {$this->table} ORDER BY name ASC";
        $categories = $this->pdo->query($sql, PDO::FETCH_CLASS, $this->class)->fetchAll();
        $results = [];
        foreach ($categories as $category) {
            $results[$category->getID()] = $category->getName();
        }
        return $results;
    }
}