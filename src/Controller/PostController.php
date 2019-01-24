<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\FormBuilder\CommentFormBuilder;
use App\Form\FormBuilder\PostFormBuilder;
use App\Form\FormHandler\CommentFormHandler;
use App\Form\FormHandler\PostFormHandler;
use OCFram\Controller\Controller;
use OCFram\Exception\NotFoundHTTPException;
use OCFram\HTTPComponents\HTTPRequest;

const POSTS_TO_DISPLAY_PER_PAGE = 5;
const POSTS_CONTENT_TRUNCACTE_AFTER_X_CHARS = 300;

class PostController extends Controller {

    public function executeIndex(HTTPRequest $request) {

        $pageTitle = "Accueil";

        $postsPagination = array();
        $postsPagination['offset'] = $request->getData('offset');
        $postsPagination['maxResultsPerPage'] = POSTS_TO_DISPLAY_PER_PAGE;

        $em = $this->getEntityManager();

        $posts = $em->getRepository('Post')
            ->getPaginatedList($postsPagination['offset'],
                POSTS_TO_DISPLAY_PER_PAGE,
                ['createdAt' => 'DESC']);

        $postsPagination['nbrResults'] = $em->getRepository('Post')->countAll();

        // We will limit post's content size for index action
        foreach ($posts as $post) {

            if (strlen($post->getContent()) > POSTS_CONTENT_TRUNCACTE_AFTER_X_CHARS) {
                $content = substr($post->getContent(), 0, POSTS_CONTENT_TRUNCACTE_AFTER_X_CHARS) . '...';
                $post->setContent($content);
            }
        }

        // Get how many comments / post

        return $this->render('posts/index.html.twig', [
            'title' => $pageTitle,
            'posts' => $posts,
            'pagination' => $postsPagination,
            'user' => $this->getApp()->getUser(),
            'flashMessage' => $this->getApp()->getUser()->getFlash()]);
    }

    public function executeShow(HTTPRequest $request)
    {
        $em = $this->getEntityManager();

        $postId = (int) $request->getData('post_id');

        $post = $em->getRepository('Post')->findWithComments($postId);

        if ($post == false) {
            throw new NotFoundHTTPException();
        }

        // Handle comment add form
        $comment = new Comment();

        $commentFormBuilder = new CommentFormBuilder($comment);
        $commentFormBuilder->getFormDatasFromRequest($request);
        $commentForm = $commentFormBuilder->getForm();
        CommentFormHandler::handle($commentForm, $this->getApp());

        return $this->render('posts/post.html.twig',
            ['post' => $post,
                'commentForm' => $commentForm->createView(),
                'user' => $this->getApp()->getUser(),
                'flashMessage' => $this->getApp()->getUser()->getFlash()]);
    }

    public function executeAdd(HTTPRequest $request)
    {

        $post = new Post();
        $postFormBuilder = new PostFormBuilder($post);
        $postFormBuilder->getFormDatasFromRequest($request);
        $postForm = $postFormBuilder->getForm();

        $postCreationSuccessful = PostFormHandler::handle($postForm, $this->getApp());

        if ($postCreationSuccessful) {
            $this->getApp()->getHTTPResponse()->redirect('/');
        }

        return $this->render('posts/add.html.twig',
            ['postForm' => $postForm->createView(),
                'user' => $this->getApp()->getUser(),
                'flashMessage' => $this->getApp()->getUser()->getFlash()]);
    }

    // TODO : Refactor sur cette methode, code dupliqué
    public function executeEdit(HTTPRequest $request)
    {
        $em = $this->getEntityManager();
        $postId = (int) $request->getData('post_id');
        $post = $em->getRepository('Post')->findOneById($postId);

        if ($post == false) {
            throw new NotFoundHTTPException();
        }

        $postFormBuilder = new PostFormBuilder($post);

        // Fill form with post entity datas if httprequest method is not post
        if ($request->method() == "POST") {
            $postFormBuilder->getFormDatasFromRequest($request);
        } else {
            $postFormBuilder->buildFormWithEntityDatas();
        }

        $postForm = $postFormBuilder->getForm();
        $postCreationSuccessful = PostFormHandler::handle($postForm, $this->getApp(), $edit = true);

        if ($postCreationSuccessful) {
            $this->getApp()->getHTTPResponse()->redirect('/');
        }

        return $this->render('posts/edit.html.twig',
            ['post' => $post,
                'postForm' => $postForm->createView(),
                'user' => $this->getApp()->getUser(),
                'flashMessage' => $this->getApp()->getUser()->getFlash()]);
    }

    // TODO : Gerer l'erreur en cas d'echec suppression
    public function executeDelete(HTTPRequest $request)
    {
        $em = $this->getEntityManager();
        $postId = (int) $request->getData('post_id');
        $post = $em->getRepository('Post')->findOneById($postId);

        if ($post == false) {
            throw new NotFoundHTTPException();
        }

        $deleteSuccess = $em->getRepository('Post')->delete($post);

        if ($deleteSuccess) {

            $this->getApp()->getUser()->setFlash(['type' => 'success',
                'message' => 'Merci ! L\'article a bien été supprimé']);
            $this->getApp()->getHTTPResponse()->redirect('/');
        }
    }
}