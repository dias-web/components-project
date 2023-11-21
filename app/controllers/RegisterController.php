<?php

namespace App\controllers;

use App\models\User;
use League\Plates\Engine;
use Delight\Auth\Auth;

class RegisterController
{
    public function showRegisterPage()
    {
        $templates = new Engine('../app/views');

        echo $templates->render('register');
    }

    public function registerUser(Auth $auth)
    {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = new User($auth);
        $user->createUser($email, $password);
    }
}
