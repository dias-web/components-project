<?php

namespace App\controllers;

use Delight\Auth\Auth;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;

class UserController
{
    private $auth;
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    public function index()
    {
        if (!$this->auth->isLoggedIn()) {
            flash()->error('Требуется авторизация!');
            header('Location: /login');
            die();
        } else {
            $templates = new Engine('../app/views');
            echo $templates->render('users');
        }
    }
}
