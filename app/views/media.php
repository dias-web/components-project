<?php
$this->layout('layout');

if (!session_id()) {
    session_start();
}

use function Tamtamchik\SimpleFlash\flash;
?>

<main id="js-page-content" role="main" class="page-content mt-3">
    <?= flash()->display('success') ?? '' ?>
    <?= flash()->display('error') ?? '' ?>
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-image'></i> Загрузить аватар
        </h1>
    </div>
    <form action="/media/<?= $user['id'] ?>" method="post" enctype="multipart/form-data">
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Текущий аватар</h2>
                        </div>
                        <div class="panel-content">
                            <div class="form-group">
                                <img src="/uploads/<?= $user['avatar'] ?>" alt="" class="img-responsive" width="80">
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="example-fileinput">Выберите аватар</label>
                                <input type="file" id="example-fileinput" class="form-control-file" name="avatar">
                            </div>
                            <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                <button class="btn btn-warning" type="submit">Загрузить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

