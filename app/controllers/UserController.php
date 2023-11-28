<?php

namespace App\controllers;

use App\models\QueryBuilder;
use Delight\Auth\Auth;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;

class UserController
{
    private $auth;
    private $queryBuilder;

    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }

    public function index()
    {
        if (!$this->auth->isLoggedIn()) {
            flash()->error('Требуется авторизация!');
            header('Location: /login');
            die();
        } else {
            $templates = new Engine('../app/views');
            echo $templates->render('users', ['auth' => $this->auth]);
        }
    }

    public function showEditPage()
    {
        if (!$this->auth->isLoggedIn()) {
            flash()->error('Требуется авторизация!');
            header('Location: /login');
            die();
        } else {
            $templates = new Engine('../app/views');
            echo $templates->render('edit', ['auth' => $this->auth]);
        }
    }

    public function showCreatePage()
    {
        if (!$this->auth->isLoggedIn()) {
            flash()->error('Требуется авторизация!');
            header('Location: /login');
            die();
        } else {
            $templates = new Engine('../app/views');
            echo $templates->render('create');
        }
    }

    public function createNewUser()
    {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $username = $_POST['username'];
            $phone = $_POST['phone'];
            $job = $_POST['job'];
            $address = $_POST['address'];
            $status = $_POST['status'];
            //$avatar = $_POST['avatar'];
            $vk = $_POST['vk'];
            $tg = $_POST['tg'];
            $insta = $_POST['insta'];

            $userId = $this->auth->register($email, $password, $username);

            $this->queryBuilder->insert('users_profile', [
                'user_id' => $userId,
                'job' => $job,
                'phone' => $phone,
                'address' => $address,
                'status' => $status,
                'vk' => $vk,
                'tg' => $tg,
                'insta' => $insta
            ]);

            flash()->success('Пользователь успешно добавлен!');
            header('Location: /');
            exit();
        } catch (InvalidEmailException) {
            flash()->error('Неверный формат адреса электронной почты.');
        } catch (InvalidPasswordException) {
            flash()->error('Неверный пароль.');
        } catch (UserAlreadyExistsException) {
            flash()->error('Такой пользователь уже существует!');
        } catch (TooManyRequestsException) {
            flash()->error('Слишком много запросов. Пожалуйста, повторите попытку позже.');
        }
        header('Location: /create');
        exit();
    }
}
