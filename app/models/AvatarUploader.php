<?php
namespace App\models;

use Imagine\Image\Box;
use Imagine\Gd\Imagine;

class AvatarUploader
{
    public function upload($imageFile)
    {
        if ($imageFile['error'] !== UPLOAD_ERR_OK) {
            throw new \Exception('Ошибка при загрузке файла.');
        }

        $imagine = new Imagine();
        $image = $imagine->open($imageFile['tmp_name']);

        $image->resize(new Box(50, 50));

        $filename = uniqid() . '.' . pathinfo($imageFile['name'], PATHINFO_EXTENSION);
        $image->save('./uploads/' . $filename);

        return $filename;
    }
}