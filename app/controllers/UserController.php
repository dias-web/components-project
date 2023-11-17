<?php

namespace App\controllers;

use League\Plates\Engine;

class UserController
{
    public function index()
    {
        $templates = new Engine('../app/views');

        echo $templates->render('edit');
    }
}
