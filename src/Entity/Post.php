<?php
namespace App\Entity;

use OCFram\Entities\BaseEntity;
use OCFram\User\BaseUser;

class Post extends BaseEntity
{
    protected $author,
        $heading,
        $title,
        $content,
        $createdAt,
        $updatedAt;

    const INVALID_AUTHOR = 1;
    const INVALID_HEADING = 2;
    const INVALID_TITLE = 3;
    const INVALID_CONTENT = 4;

    public function isValid()
    {
        return !(empty($this->author) || empty($this->title) || empty($this->content));
    }

    public function setAuthor(BaseUser $author)
    {
        $this->$author = $author;
    }

    public function setHeading($heading)
    {
        if (!is_string($heading) || empty($heading))
        {
            $this->errors[] = self::INVALID_HEADING;
        }

        $this->heading = $heading;
    }

    public function setTitle($title)
    {
        if (!is_string($title) || empty($title))
        {
            $this->errors[] = self::INVALID_TITLE;
        }

        $this->title = $title;
    }

    public function setContent($content)
    {
        if (!is_string($content) || empty($content))
        {
            $this->errors[] = self::INVALID_CONTENT;
        }

        $this->content = $content;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }

    // GETTERS //

    public function getAuthor()
    {
        return $this->author;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getContent()
    {
        return $this->content;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
}