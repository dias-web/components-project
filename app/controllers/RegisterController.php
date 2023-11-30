<?php

namespace App\controllers;

use App\models\QueryBuilder;
use App\models\User;
use League\Plates\Engine;
use Delight\Auth\Auth;

class RegisterController
{
    private Auth $auth;
    private QueryBuilder $queryBuilder;

    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }
    public function showRegisterPage()
    {
        $templates = new Engine('../app/views');

        echo $templates->render('register');
    }

    public function registerUser()
    {
        $userModel = new User($this->auth, $this->queryBuilder);
        $result = $userModel->registerNewUser($_POST);

        if ($result) {
            header('Location: /login');
            exit();
        } else {
            header('Location: /register');
            exit();
        }
    }
}
