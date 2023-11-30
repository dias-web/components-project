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
            <i class='subheader-icon fal fa-plus-circle'></i> Редактировать
        </h1>

    </div>
    <form action="/edit/<?= $user['id'] ?>" method="post">
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Общая информация</h2>
                        </div>
                        <div class="panel-content">
                            <!-- username -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Имя</label>
                                <input type="text" id="simpleinput" class="form-control"
                                       value="<?= $user['username'] ?>" name="username">
                            </div>

                            <!-- title -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Место работы</label>
                                <input type="text" id="simpleinput" class="form-control"
                                       value="<?= $user['job'] ?>" name="job">
                            </div>

                            <!-- tel -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Номер телефона</label>
                                <input type="text" id="simpleinput" class="form-control"
                                       value="<?= $user['phone'] ?>" name="phone">
                            </div>

                            <!-- address -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Адрес</label>
                                <input type="text" id="simpleinput" class="form-control"
                                       value="<?= $user['address'] ?>" name="address">
                            </div>
                            <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                <button class="btn btn-warning" type="submit">Редактировать</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>

