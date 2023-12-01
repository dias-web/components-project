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
            <i class='subheader-icon fal fa-lock'></i> Безопасность
        </h1>
    </div>
    <form action="/security/<?= $user['id'] ?>" method="post">
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Обновление эл. адреса и пароля</h2>
                        </div>
                        <div class="panel-content">
                            <!-- email -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Email</label>
                                <input type="text" id="simpleinput" class="form-control"
                                       name="email" value="<?= $user['email'] ?>">
                            </div>
                            <!-- password -->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Пароль</label>
                                <input type="password" id="simpleinput" class="form-control"
                                       name="password">
                            </div>
                            <!-- password confirmation-->
                            <div class="form-group">
                                <label class="form-label" for="simpleinput">Подтверждение пароля</label>
                                <input type="password" id="simpleinput" class="form-control"
                                       name="repeat-password">
                            </div>
                            <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                <button class="btn btn-warning" type="submit">Изменить</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
