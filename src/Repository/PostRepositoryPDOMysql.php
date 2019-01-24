<?php

namespace App\Repository;

use App\Entity\Post;
use OCFram\Entities\Utils\ArrayToEntityConverter;
use OCFram\Repository\BaseRepositoryPDOMysql;

class PostRepositoryPDOMysql extends BaseRepositoryPDOMysql
{

    public function getPaginatedList($offset = -1, $limit = -1, array $orderBy = null) {

        $sqlQuery = "SELECT * FROM ".$this->getTableName();

        if ($orderBy != null) {

            $orderBySqlQuery = "ORDER BY ";

            $orderByElementCounter = 0;
            foreach ($orderBy as $orderByAttribute => $orderByValue) {

                if (!property_exists("App\\Entity\\". $this->getEntityName(), $orderByAttribute)) {
                    throw new \RuntimeException("Cannot order by attribute ".$orderByAttribute
                                                            .", entity ". $this->getEntityName() . " doesn't have this attribute.");
                }

                $orderBySqlQuery .= $orderByAttribute . ' ' . $orderByValue;

                if ($orderByElementCounter > 0 && sizeof($orderBy) <= $orderByElementCounter) {
                    $orderBySqlQuery .= ", ";
                }

                $orderByElementCounter++;
            }

            $sqlQuery .= " ".$orderBySqlQuery;

        }

        if (($offset != -1 && $offset !== null) || ($limit != -1 && $limit !== null)) {
            $sqlQuery .= ' LIMIT '.(int) $limit.' OFFSET '.(int) $offset;
        }

        $results = $this->getDb()->query($sqlQuery)->fetchAll(\PDO::FETCH_ASSOC);

        if ($results) {
            $hydratedEntities = ArrayToEntityConverter::convert($results, $this->getEntityName());

            return $hydratedEntities;
        }

        return false;

    }

    public function findWithComments($postId) {

        $postId = (int) $postId;

        if (!$postId) {
            throw new \InvalidArgumentException("postId must be a valid integer.");
        }

        $sqlQuery = "SELECT * FROM post WHERE id = :postId";

        $stmt = $this->getDb()->prepare($sqlQuery);
        $stmt->bindValue('postId', $postId, \PDO::PARAM_INT);
        $stmt->execute();

        $post = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $stmt->closeCursor();

        if ($post != false) {

            $post = ArrayToEntityConverter::convert($post, $this->getEntityName());
            $post = $post[0];

            // Grab post's comments
            $sqlQuery = "SELECT * FROM comment WHERE post_id = :postId AND isValid = 1 ORDER BY createdAt";
            $stmt = $this->getDb()->prepare($sqlQuery);
            $stmt->bindValue('postId', $postId, \PDO::PARAM_INT);
            $stmt->execute();

            $comments = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $stmt->closeCursor();

            $comments = ArrayToEntityConverter::convert($comments, 'Comment');

            $post->setComments($comments);

            return $post;

        }

        return false;
    }

    public function save(Post $post, $edit = false) {

        // If we are editing post, we don't change author id and update updatedAt field.
        $sqlQueryInsertInto = "INSERT INTO post (title, heading, content, createdAt, updatedAt";
        $sqlQueryValues = "VALUES (:title, :heading, :content, :createdAt, :updatedAt";

        if ($edit === true) {
            $sqlQueryInsertInto .= ") ";
            $sqlQueryValues .= ")";
        } else {
            $sqlQueryInsertInto .= ", author_id) ";
            $sqlQueryValues .= ", :author_id)";
        }

        $sqlQuery = $sqlQueryInsertInto . $sqlQueryValues;

        $stmt = $this->getDb()->prepare($sqlQuery);

        $title = $post->getTitle();
        $heading = $post->getHeading();
        $content = $post->getContent();
        $createdAt = $post->getCreatedAt()->format('Y-m-d H:i:s');

        var_dump($sqlQuery);

        if ($edit === true) {
            $updatedAt = $post->getUpdatedAt()->format('Y-m-d H:i:s');
        } else {
            $authorId = $post->getAuthorId();
            $stmt->bindParam('author_id', $authorId, \PDO::PARAM_INT);
            $updatedAt = new \DateTime();
            $updatedAt = $updatedAt->format('Y-m-d H:i:s');
        }

        $stmt->bindParam('title', $title, \PDO::PARAM_STR);
        $stmt->bindParam('heading', $heading, \PDO::PARAM_STR);
        $stmt->bindParam('content', $content, \PDO::PARAM_STR);
        $stmt->bindParam('createdAt', $createdAt, \PDO::PARAM_STR);
        $stmt->bindParam('updatedAt', $updatedAt, \PDO::PARAM_STR);

        return $stmt->execute();
    }

    public function delete(Post $post) {

        $sqlQuery = "DELETE FROM post WHERE id = :id";

        $stmt = $this->getDb()->prepare($sqlQuery);
        $postId = $post->getId();
        $stmt->bindParam('id', $postId, \PDO::PARAM_INT);

        return $stmt->execute();
    }
}