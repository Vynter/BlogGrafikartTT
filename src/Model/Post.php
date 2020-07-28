<?php

namespace App\Model;

use App\Helpers\Text;
use DateTime;

class Post
{
    private $id;
    private $name;
    private $content;
    private $created_at;
    private $slug;
    private $categories = [];

    public function getName(): ?string
    {
        return $this->name;
    }
    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }
    public function setContent(string $content): self
    {
        $this->content = $content;
        return $this;
    }
    public function getExcerpt(): ?string
    {
        if ($this->content === null) {
            return null;
        }

        return nl2br(htmlentities(Text::excerpt($this->content, 60)));
    }
    public function getCreatedAt(): DateTime
    {
        return new DateTime($this->created_at);
    }
    public function getID(): ?int
    {
        return $this->id;
    }
    public function getSlug(): ?string /*return null ou string */
    {
        return $this->slug;
    }
    public function getFormattedContent(): string
    {
        return nl2br(e($this->content));
    }
    /**
     * @return Category[]
     */
    public function getCategories(): array
    {
        return $this->categories;
    }

    public function addCategory(Category $category): void
    {
        $this->categories[] = $category;
        $category->setPost($this); // sert a rien
    }
}