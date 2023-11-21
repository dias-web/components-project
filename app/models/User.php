<?php

namespace App\models;

use Delight\Auth\Auth;

class User
{
    private $auth;

    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    public function createUser ($email, $password)
    {
        try {

            $userId = $this->auth->register($email, $password, null);
            header('Location: /login');

        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Invalid email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Invalid password');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('User already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function loginUser($email, $password)
    {
        try {
            $this->auth->login($email, $password);
            header('Location: /');
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            die('Wrong email address');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            die('Wrong password');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            die('Email not verified');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }
}