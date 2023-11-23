<?php

namespace App\controllers;

use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use League\Plates\Engine;
use Delight\Auth\Auth;
use function Tamtamchik\SimpleFlash\flash;

class RegisterController
{
    private $auth;
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    public function showRegisterPage(): void
    {
        $templates = new Engine('../app/views');

        echo $templates->render('register');
    }

    public function registerUser(): void
    {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $userId = $this->auth->register($email, $password, null);
            flash()->success('Пользователь успешно зарегистрирован!');
            header('Location: /login');
            exit();
        }
        catch (InvalidEmailException) {
            flash()->error('Неверный формат адреса электронной почты.');
        }
        catch (InvalidPasswordException) {
            flash()->error('Неверный пароль.');
        }
        catch (UserAlreadyExistsException) {
            flash()->error('Такой пользователь уже существует!');
        }
        catch (TooManyRequestsException) {
            flash()->error('Слишком много запросов. Пожалуйста, повторите попытку позже.');
        }
        header('Location: /register');
        exit();
    }
}
