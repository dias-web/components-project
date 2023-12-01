<?php

namespace App\controllers;

use App\models\QueryBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;

class SecurityController
{
    private $auth;
    private $queryBuilder;

    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }

    public function showSecurityPage($id)
    {
        global $container;

        if (!$this->auth->hasRole(\Delight\Auth\Role::ADMIN) && $this->auth->getUserId() != $id) {
            flash()->error('Недостаточно прав для редактирования!');
            header('Location: /');
            exit();
        }

        try {
            $qb = $container->get(QueryBuilder::class);
            $user = $qb->getOne('users', 'users_profile', $id);
        } catch (\DI\DependencyException $e) {
            flash()->error('Ошибка: ' . $e->getMessage());
        } catch (\DI\NotFoundException $e) {
            flash()->error('Ошибка: ' . $e->getMessage());
        }

        if (!$this->auth->isLoggedIn()) {
            flash()->error('Требуется авторизация!');
            header('Location: /login');
            die();
        } else {
            $templates = new Engine('../app/views');
            echo $templates->render('security', ['user' => $user]);
        }
    }

    public function editSecurityInfoOfUser($id)
    {
        try {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $repeatPassword = $_POST['repeat-password'];

            if ($password !== $repeatPassword) {
                throw new \Exception('Пароли не совпадают!');
            }

            if ($email !== '' && $password !== '') {
                $this->queryBuilder->update('users', $id, [
                    'email' => $_POST['email'],
                    'password' => password_hash($_POST['password'], PASSWORD_DEFAULT)
                ]);
            }

            flash()->success('Данные безопасности обновлены!');
        } catch (\Exception $e) {
            flash()->error('Ошибка: ' . $e->getMessage());
        }

        header('Location: /security/' . $id);
        exit();
    }

}
