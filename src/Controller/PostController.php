<?php

namespace App\Controller;

use OCFram\Controller\Controller;
use OCFram\Exception\NotFoundHTTPException;
use OCFram\HTTPComponents\HTTPRequest;

const POSTS_TO_DISPLAY = 5;
const POSTS_CONTENT_TRUNCACTE_AFTER_X_CHARS = 300;

class PostController extends Controller {

    public function executeIndex(HTTPRequest $request) {

        $pageTitle = "Accueil";

        $em = $this->getEntityManager();

        $posts = $em->getRepository('Post')->findAll();

        return $this->render('posts/index.html.twig', [
            'title' => $pageTitle, 'posts' => $posts]);
    }

    public function executeShow(HTTPRequest $request)
    {
        $em = $this->getEntityManager();

        $postId = (int) $request->getData('id');

        $post = $em->getRepository('Post')->findOneById($postId);

        if ($post == false) {
            throw new NotFoundHTTPException();
        }

        return $this->render('posts/post.html.twig',['post' => $post]);
    }
}