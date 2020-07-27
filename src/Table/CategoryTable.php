<?php

namespace App\Table;

use PDO;
use App\Model\Category;
use App\Table\Exception\NotFoundException;

class CategoryTable extends Table
{
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
    }
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
}