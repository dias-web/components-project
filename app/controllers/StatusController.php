<?php

namespace App\controllers;

use App\models\QueryBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;

class StatusController
{
    private $auth;
    private $queryBuilder;

    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }

    public function showStatusPage($id)
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
            echo $templates->render('status', ['user' => $user]);
        }
    }

    public function editStatus($id)
    {
        try {
            $status = $_POST['status'];

            if ($status !== '') {
                $this->queryBuilder->update('users_profile', $id, [
                    'status' => $status,
                ]);
            }

            flash()->success('Статус обновлен!');
        } catch (\Exception $e) {
            flash()->error('Ошибка: ' . $e->getMessage());
        }

        header('Location: /');
        exit();
    }



}
