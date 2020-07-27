<?php

namespace App\Model;

class Category
{
    private $id;
    private $name;
    private $slug;
    private $post_id;
    private $post;

    public function getID(): ?int
    {
        return $this->id;
    }
    public function getName(): ?string
    {
        return $this->name;
    }
    public function getSlug(): ?string
    {
        return $this->slug;
    }
    public function getPostID(): ?int
    {
        return $this->post_id;
    }
    public function setPost(Post $post)
    {
        $this->post = $post;
    }
}