<?php

namespace App\controllers;

use App\models\AvatarUploader;
use App\models\QueryBuilder;
use Delight\Auth\Auth;
use League\Plates\Engine;
use function Tamtamchik\SimpleFlash\flash;

class MediaController
{
    private $auth;
    private $queryBuilder;

    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }

    public function showMediaPage($id)
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
            echo $templates->render('media', ['user' => $user]);
        }
    }

    public function editAvatar($id)
    {
        try {
            if (isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
                $avatarUploader = new AvatarUploader();

                $userData = $this->queryBuilder->getOne('users', 'users_profile', $id);
                $currentAvatar = $userData['avatar'] ?? null;

                $avatarFilename = $avatarUploader->upload($_FILES['avatar']);

                $this->queryBuilder->update('users_profile', $id, [
                    'avatar' => $avatarFilename
                ]);

                if ($currentAvatar && file_exists('uploads/' . $currentAvatar)) {
                    unlink('uploads/' . $currentAvatar);
                }

                flash()->success('Аватар успешно обновлен!');
            } else {
                throw new \Exception('Файл не загружен или произошла ошибка при загрузке.');
            }
        } catch (\Exception $e) {
            flash()->error('Ошибка при загрузке файла: ' . $e->getMessage());
        }

        header('Location: /media/'.$id);
        exit();
    }

}
