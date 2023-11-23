<?php

namespace App\controllers;

use Delight\Auth\Auth;
use Delight\Auth\EmailNotVerifiedException;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;

class LoginController
{
    private $auth;
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }

    public function showLoginPage(): void
    {
        $templates = new Engine('../app/views');

        echo $templates->render('login');
    }

    public function login(): void
    {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $this->auth->login($email, $password);
            header('Location: /');
            exit();
        }
        catch (InvalidEmailException) {
            flash()->error('Неверный адрес электронной почты.');
        }
        catch (InvalidPasswordException) {
            flash()->error('Неверный пароль.');
        }
        catch (EmailNotVerifiedException) {
            flash()->error('Электронной почта не верифицирована.');
        }
        catch (TooManyRequestsException) {
            flash()->error('Слишком много запросов. Пожалуйста, повторите попытку позже.');
        }

        header('Location: /login');
        exit();

    }
}
