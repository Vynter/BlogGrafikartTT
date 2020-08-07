<?php

namespace App\Table;

use PDO;
use App\Model\Post;
use App\Model\Category;
use App\PaginatedQuery;
use App\Table\Exception\NotFoundException;
use Exception;

class PostTable extends Table
{
    protected $table = "post";
    protected $class = Post::class;
    /*
    public function find(int $id): Post
    {
        $query = $this->pdo->prepare(
            'SELECT * 
            FROM post
            WHERE id=:id
            '
        );
        $query->execute(['id' => $id]);
        $query->setFetchMode(PDO::FETCH_CLASS, Post::class);
        $result = $query->fetch();
        if ($result === false) {
            throw new NotFoundException('post', $id);
        }
        return $result;
    }*/
    public function create(Post $post, array $categories): void
    {
        $this->pdo->beginTransaction(); // pas nécessaire mais par sécurité
        $query = $this->pdo->prepare("INSERT INTO {$this->table} set  name= :name,slug =:slug, content =:content, created_at = :created_at");
        $ok = $query->execute([
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreated_at()->format('Y-m-d H:i:s') // trés important
        ]);
        if ($ok === false) {
            throw new Exception("Impossible de crée l'enregistrement {$post->getID()} dans la table {$this->table}");
        }
        $post->setID($this->pdo->lastInsertId());
        $this->pdo->exec('DELETE FROM post_category WHERE post_id =' . $post->getID());
        $query2 = $this->pdo->prepare('INSERT INTO post_category SET post_id = :idp , category_id = :idc');
        foreach ($categories as $category) {
            $query2->execute([
                'idp' => $post->getID(),
                'idc' => $category
            ]);
        }
        $this->pdo->commit(); // pas nécessaire mais par sécurité
    }

    public function update(Post $post, array $categories): void
    {
        $this->pdo->beginTransaction(); // pas nécessaire mais par sécurité
        $query = $this->pdo->prepare("UPDATE {$this->table} SET name= :name,slug =:slug, content =:content, created_at = :created_at WHERE id = :id");
        $ok = $query->execute([
            'id' => $post->getID(),
            'name' => $post->getName(),
            'slug' => $post->getSlug(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreated_at()->format('Y-m-d H:i:s') // trés important
        ]);
        if ($ok === false) {
            throw new Exception("Impossible de supprimer l'enregistrement {$post->getID()} dans la table {$this->table}");
        }
        $this->pdo->exec('DELETE FROM post_category WHERE post_id =' . $post->getID());
        $query2 = $this->pdo->prepare('INSERT INTO post_category SET post_id = :idp , category_id = :idc');
        foreach ($categories as $category) {
            $query2->execute([
                'idp' => $post->getID(),
                'idc' => $category
            ]);
        }
        $this->pdo->commit(); // pas nécessaire mais par sécurité
    }

    public function delete(int $id): void
    {
        $query = $this->pdo->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $ok = $query->execute([$id]);
        if ($ok === false) {
            throw new Exception("Impossible de supprimer l'enregistrement $id dans la table {$this->table}");
        }
    }
    public function findPaginated()
    {
        $paginatedQuery = new PaginatedQuery(
            "SELECT * FROM post ORDER BY created_at DESC",
            'SELECT count(id) FROM post LIMIT 1',
            $this->pdo
        );
        $posts = $paginatedQuery->getItems(Post::class);
        /** @var call methode from a class without using variable */
        //
        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts, $paginatedQuery];
    }

    public function findPaginatedForCategory($categoryID)
    {
        $PaginatedQuery = new PaginatedQuery(
            "SELECT p.* 
                FROM post p 
                JOIN post_category pc ON pc.post_id = p.id 
                WHERE pc.category_id = {$categoryID}
                ORDER BY created_at DESC",
            "SELECT count(category_id) FROM post_category WHERE category_id= {$categoryID}"
        );

        /** @var Post[] */
        $posts = $PaginatedQuery->getItems(Post::class);

        (new CategoryTable($this->pdo))->hydratePosts($posts);
        return [$posts, $PaginatedQuery];
    }
}