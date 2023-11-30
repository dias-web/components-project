<?php

namespace App\controllers;

use App\models\QueryBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;

class EditController
{
    private $auth;
    private $queryBuilder;

    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }

    public function showEditPage($id)
    {
        global $container;

        if (!$this->auth->hasRole(\Delight\Auth\Role::ADMIN) && $this->auth->getUserId() != $id) {
            flash()->error('Недостаточно прав для редактирования!');
            header('Location: /');
            exit();
        }

        try {
            $qb = $container->get(QueryBuilder::class);
            $user = $qb->getOne('users_profile', $id);
        } catch (\DI\DependencyException $e) {
        } catch (\DI\NotFoundException $e) {
        }

        if (!$this->auth->isLoggedIn()) {
            flash()->error('Требуется авторизация!');
            header('Location: /login');
            die();
        } else {
            $templates = new Engine('../app/views');
            echo $templates->render('edit', ['user' => $user]);
        }
    }

    public function editUser($id)
    {
        if (!$this->auth->isLoggedIn()) {
            flash()->error('Требуется авторизация!');
            header('Location: /login');
            exit();
        }

        if (!$this->auth->hasRole(\Delight\Auth\Role::ADMIN) && $this->auth->getUserId() != $id) {
            flash()->error('Недостаточно прав для редактирования!');
            header('Location: /');
            exit();
        }

        try {
            $this->queryBuilder->update('users_profile', $id, [
                'username' => $_POST['username'],
                'job' => $_POST['job'],
                'phone' => $_POST['phone'],
                'address' => $_POST['address']
            ]);

            flash()->success('Информация пользователя успешно обновлена!');
        } catch (\Exception $e) {
            flash()->error('Ошибка при обновлении: ' . $e->getMessage());
        }

        header('Location: /edit/' . $id);
        exit();
    }
}
