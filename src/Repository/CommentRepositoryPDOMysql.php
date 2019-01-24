<?php

namespace App\Repository;

use App\Entity\Comment;
use OCFram\Entities\Utils\ArrayToEntityConverter;
use OCFram\Repository\BaseRepositoryPDOMysql;

class CommentRepositoryPDOMysql extends BaseRepositoryPDOMysql
{

    public function save(Comment $comment)
    {

        $sqlQuery = "INSERT INTO comment (post_id, content, createdAt) VALUES (:postId, :content, :createdAt)";
        $stmt = $this->getDb()->prepare($sqlQuery);

        $createdAt = $comment->getCreatedAt()->format('Y-m-d H:i:s');
        $postId = $comment->getPostId();
        $content = $comment->getContent();

        $stmt->bindParam('postId', $postId, \PDO::PARAM_INT);
        $stmt->bindParam('content', $content, \PDO::PARAM_STR);
        $stmt->bindParam('createdAt', $createdAt, \PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function findNotValidated()
    {
        $sqlQuery = "SELECT * FROM comment WHERE isValid = 0";

        $stmt = $this->getDb()->query($sqlQuery);

        $comments = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $comments = ArrayToEntityConverter::convert($comments, 'Comment');

        $stmt->closeCursor();

        return $comments;

    }

    public function validate(Comment $comment)
    {
        $sqlQuery = "UPDATE comment SET isValid = 1 WHERE id = :id";
        $stmt = $this->getDb()->prepare($sqlQuery);

        $commentId = $comment->getId();

        $stmt->bindParam('id', $commentId, \PDO::PARAM_INT);

        return $stmt->execute();
    }
}