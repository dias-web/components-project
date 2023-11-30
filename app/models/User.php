<?php

namespace App\models;

use Delight\Auth\Auth;
use Delight\Auth\AuthError;
use Delight\Auth\InvalidEmailException;
use Delight\Auth\InvalidPasswordException;
use Delight\Auth\TooManyRequestsException;
use Delight\Auth\UserAlreadyExistsException;
use function Tamtamchik\SimpleFlash\flash;

class User
{
    private $auth;
    private $queryBuilder;

    public function __construct(Auth $auth, QueryBuilder $queryBuilder)
    {
        $this->auth = $auth;
        $this->queryBuilder = $queryBuilder;
    }

    public function registerNewUser($data)
    {
        try {
            $email = $data['email'];
            $password = $data['password'];

            $userId = $this->auth->register($email, $password, null);

            $this->queryBuilder->insert('users_profile', [
                'user_id' => $userId,
            ]);

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
        catch (AuthError $e) {
            flash()->error('Ошибка регистрации: ' . $e->getMessage());
        }
        header('Location: /register');
        exit();
    }

    public function createNewUser($data, $files = null)
    {
        try {
            $email = $data['email'];
            $password = $data['password'];

            $userId = $this->auth->register($email, $password, null);

            if (isset($files['image'])) {
                $avatarUploader = new AvatarUploader();
                $avatarFilename = $avatarUploader->upload($files['image']);

                $this->queryBuilder->insert('users_profile', [
                    'user_id' => $userId,
                    'username' => $data['username'],
                    'job' => $data['job'],
                    'phone' => $data['phone'],
                    'address' => $data['address'],
                    'status' => $data['status'],
                    'vk' => $data['vk'],
                    'tg' => $data['tg'],
                    'insta' => $data['insta'],
                    'avatar' => $avatarFilename
                ]);
            }
            flash()->success('Пользователь успешно добавлен!');
            return true;

        } catch (InvalidEmailException) {
            flash()->error('Неверный формат адреса электронной почты.');
        } catch (InvalidPasswordException) {
            flash()->error('Неверный пароль.');
        } catch (UserAlreadyExistsException) {
            flash()->error('Такой пользователь уже существует!');
        } catch (TooManyRequestsException) {
            flash()->error('Слишком много запросов. Пожалуйста, повторите попытку позже.');
        } catch (\Exception $e) {
            flash()->error('Ошибка при загрузке файла: ' . $e->getMessage());
        }
        return false;
    }
}
