<?php
namespace App\Entity;

use OCFram\Entities\BaseEntity;
use OCFram\User\BaseUser;

class Post extends BaseEntity
{
    protected $author,
        $authorId,
        $heading,
        $title,
        $content,
        $createdAt,
        $updatedAt,
        $comments;

    const INVALID_AUTHOR = 1;
    const INVALID_HEADING = 2;
    const INVALID_TITLE = 3;
    const INVALID_CONTENT = 4;

    public function __construct(array $datas = [])
    {
        $this->createdAt = new \DateTime();
        $this->updatedAt = new \DateTime();
        parent::__construct($datas);
    }

    public function setAuthor(BaseUser $author)
    {
        $this->author = $author;
    }

    public function setAuthorId($author)
    {
        $this->author = $author;
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

    public function setComments(array $comments)
    {
        $this->comments = $comments;
    }

    // GETTERS //

    public function getAuthor()
    {
        return $this->author;
    }

    public function getAuthorId()
    {
        return $this->authorId;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function getHeading()
    {
        return $this->heading;
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

    public function getComments()
    {
        return $this->comments;
    }

}