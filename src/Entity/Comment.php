<?php

namespace App\Entity;

use \OCFram\Entities\BaseEntity;

class Comment extends BaseEntity
{

    const INVALID_AUTHOR = 1;
    const INVALID_CONTENT = 2;

    protected $postId,
        $author,
        $content,
        $createdAt,
        $isValid = false;

    public function __construct(array $datas = [])
    {
        parent::__construct($datas);

        if ($this->createdAt == null) {
            $this->createdAt = new \DateTime();
        }

    }

    public function setPostId($postId)
    {
        $this->postId = (int)$postId;
    }

    public function setAuthor($author)
    {

        if (!is_string($author) || empty($author)) {
            $this->errors[] = self::INVALID_AUTHOR;
        }

        $this->author = $author;

    }

    public function setContent($content)
    {
        if (!is_string($content) || empty($content)) {
            $this->errors[] = self::INVALID_CONTENT;
        }

        $this->content = $content;

    }

    public function setDate(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function getPostId()
    {
        return $this->postId;
    }

    public function getAuthor()
    {
        return $this->author;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getIsValid()
    {
        return $this->isValid;
    }
}