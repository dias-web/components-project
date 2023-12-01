<?php
$this->layout('layout');

if (!session_id()) {
    session_start();
}

$status = [
    'online' => 'Онлайн',
    'away' => 'Отошел',
    'do_not_disturb' => 'Не беспокоить'
];
use function Tamtamchik\SimpleFlash\flash;
?>
<main id="js-page-content" role="main" class="page-content mt-3">

    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-sun'></i> Установить статус
        </h1>

    </div>
    <form action="/status/<?= $user['id'] ?>" method="post">
        <div class="row">
            <div class="col-xl-6">
                <div id="panel-1" class="panel">
                    <div class="panel-container">
                        <div class="panel-hdr">
                            <h2>Установка текущего статуса</h2>
                        </div>
                        <div class="panel-content">
                            <div class="row">
                                <div class="col-md-4">
                                    <!-- status -->
                                    <div class="form-group">
                                        <label class="form-label" for="example-select">Выберите статус</label>
                                        <select class="form-control" id="example-select" name="status">
                                            <?php foreach ($status as $key => $item) : ?>
                                                <option <?= $key === $user['status'] ? 'selected' : '' ?> value="<?= $key ?>"><?= $item ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                                    <button class="btn btn-warning" type="submit">Set Status</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</main>
