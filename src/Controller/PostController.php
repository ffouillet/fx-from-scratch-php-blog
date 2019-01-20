<?php

namespace App\Controller;

use OCFram\Controller\Controller;
use OCFram\HTTPComponents\HTTPRequest;

class NewsController extends Controller {

    public function executeIndex(HTTPRequest $request) {

        return $this->render('home.html.twig');
    }
}