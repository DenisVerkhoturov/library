<?php

/**
 * @var \application\models\Author $model
 */

$meta = $model::meta();
?>

<form class="form" method="post" action="">
    <div class="form-field field">
        <label class="field-label">Имя</label>
        <input class="field-input" id="" type="text"
               name="first_name"
               value="<?= $model->first_name ?>">
        <p class="field-description"></p>
    </div>
    <div class="form-field field">
        <label class="field-label">Отчество / второе имя</label>
        <input class="field-input" id="" type="text"
               name="middle_name"
               value="<?= $model->middle_name ?>">
        <p class="field-description"></p>
    </div>
    <div class="form-field field">
        <label class="field-label">Фамилия</label>
        <input class="field-input" id="" type="text"
               name="last_name"
               value="<?= $model->last_name ?>">
        <p class="field-description"></p>
    </div>
    <input class="button form-submit" type="submit" value="Сохранить">
</form>
