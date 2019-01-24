<?php

namespace App\Controller;

use App\Form\FormBuilder\LoginFormBuilder;
use App\Form\FormBuilder\RegisterFormBuilder;
use App\Form\FormHandler\LoginFormHandler;
use App\Form\FormHandler\RegisterFormHandler;
use OCFram\Controller\Controller;
use OCFram\HTTPComponents\HTTPRequest;

class SecurityController extends Controller
{
    public function executeLogin(HTTPRequest $request)
    {
        $loginFormBuilder = new LoginFormBuilder(null);
        $loginFormBuilder->build();
        $loginFormBuilder->getFormDatasFromRequest($request);
        $loginForm = $loginFormBuilder->getForm();

        $loginSuccess = LoginFormHandler::handle($loginForm, $this->getApp());

        if ($loginSuccess) {
            $this->getApp()->getHTTPResponse()->redirect('/');
        }

        return $this->render(
            'security/login.html.twig',
            ['loginForm' => $loginForm->createView(),
                'user' => $this->getApp()->getUser(),
                'flashMessage' => $this->getApp()->getUser()->getFlash()]);
    }

    public function executeRegister(HTTPRequest $request)
    {

        $registerFormBuilder = new RegisterFormBuilder(null, $this->getEntityManager());
        $registerFormBuilder->build();
        $registerFormBuilder->getFormDatasFromRequest($request);
        $registerForm = $registerFormBuilder->getForm();

        $registrationSuccess = RegisterFormHandler::handle($registerForm, $this->getApp());

        if ($registrationSuccess) {
            $this->getApp()->getHTTPResponse()->redirect('/login');
        }

        return $this->render(
            'security/register.html.twig',
            ['registerForm' => $registerForm->createView(),
                'user' => $this->getApp()->getUser(),
                'flashMessage' => $this->getApp()->getUser()->getFlash()]);
    }

    public function executeLogout(HTTPRequest $request)
    {
        $this->getApp()->getUser()->logout();
        $this->getApp()->getHTTPResponse()->redirect('/');
    }

}