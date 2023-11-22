<?php

namespace App\controllers;

use Delight\Auth\Auth;

class LogOutController
{
    private $auth;
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function logout()
    {
        $this->auth->logOut();
        header('Location: /login');
    }
}
