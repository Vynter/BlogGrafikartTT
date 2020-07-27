<?php

namespace App\Table;

use PDO;
use App\Model\Post;
use App\Model\Category;
use App\PaginatedQuery;
use App\Table\Exception\NotFoundException;

class PostTable extends Table
{
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