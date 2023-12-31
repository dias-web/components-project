<?php

namespace App\controllers;

use App\models\QueryBuilder;
use App\models\User;
use Delight\Auth\Auth;
use Delight\Auth\Role;
use Delight\Auth\UnknownIdException;
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

    public function showProfilePage($id)
    {
        global $container;

        if (!$this->auth->hasRole(Role::ADMIN) && $this->auth->getUserId() != $id) {
            flash()->error('Недостаточно прав для просмотра!');
            header('Location: /');
            exit();
        }

        try {
            $qb = $container->get(QueryBuilder::class);
            $user = $qb->getOne('users', 'users_profile', $id);
        } catch (\DI\DependencyException $e) {
        } catch (\DI\NotFoundException $e) {
        }

        if (!$this->auth->isLoggedIn()) {
            flash()->error('Требуется авторизация!');
            header('Location: /login');
            die();
        } else {
            $templates = new Engine('../app/views');
            echo $templates->render('profile', ['user' => $user]);
        }
    }

    public function createNewUser()
    {
        $userModel = new User($this->auth, $this->queryBuilder);
        $result = $userModel->createNewUser($_POST, $_FILES);

        if ($result) {
            header('Location: /');
            exit();
        } else {
            header('Location: /create');
            exit();
        }
    }

    public function deleteUser($id)
    {
        try {
            if ($this->auth->hasRole(Role::ADMIN) || $this->auth->getUserId() == $id) {

                $userData = $this->queryBuilder->getOne('users', 'users_profile', $id);
                $currentAvatar = $userData['avatar'] ?? null;

                $this->auth->admin()->deleteUserById($id);
                flash()->success('Пользователь успешно удален!');

                if ($currentAvatar && file_exists('uploads/' . $currentAvatar)) {
                    unlink('uploads/' . $currentAvatar);
                }

                if ($this->auth->getUserId() == $id) {
                    $this->auth->logout();
                    header('Location: /login');
                    exit();
                }
            } else {
                flash()->error('Недостаточно прав для выполнения этой операции!');
            }
        }
        catch (UnknownIdException $e) {
            flash()->error('Ошибка! Неверный id: ' . $e->getMessage());
        }
        header('Location: /');
        exit();
    }

}
