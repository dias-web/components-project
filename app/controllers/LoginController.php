<?php

namespace App\controllers;

use App\models\User;
use Delight\Auth\Auth;
use League\Plates\Engine;

class LoginController
{
    public function showLoginPage()
    {
        $templates = new Engine('../app/views');

        echo $templates->render('login');
    }

    public function login(Auth $auth)
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = new User($auth);
        $user->loginUser($email, $password);
    }
}
