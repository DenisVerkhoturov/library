<?php

use \application\models\Author;
/**
 * @var \application\models\Book $model
 */

$meta = $model::meta();
?>

<form class="form" method="post" action="">
    <div class="form-field field">
        <label class="field-label">Обложка книги</label>
        <input class="field-input" id="" type="file"
               pattern="<?= $meta['picture']['pattern'] ?>"
               accept="image/jpeg, image/jpg, image/gif, image/png"
               name="picture"
               value="<?= $model->picture ?>">
        <p class="field-description"></p>
    </div>
    <div class="form-field field">
        <label class="field-label">Название книги</label>
        <input class="field-input" id="" type="text"
               name="title"
               value="<?= $model->title ?>">
        <p class="field-description"></p>
    </div>
    <div class="form-field field">
        <label class="field-label">Номер ISBN</label>
        <input class="field-input" id="" type="text"
               pattern="<?= $meta['isbn']['pattern'] ?>"
               name="isbn"
               value="<?= $model->isbn ?>">
        <p class="field-description"></p>
    </div>
    <div class="form-field field">
        <label class="field-label">Количество страниц</label>
        <input class="field-input" id="" type="text"
               pattern="<?= $meta['pages_amount']['pattern'] ?>"
               name="pages_amount"
               value="<?= $model->pages_amount ?>">
        <p class="field-description"></p>
    </div>
    <div class="form-field field">
        <label class="field-label">Дата публикации</label>
        <input class="field-input" id="" type="text"
               pattern="<?= $meta['published']['pattern'] ?>"
               name="published"
               value="<?= $model->published ?>">
        <p class="field-description"></p>
    </div>
    <fieldset class="form-field field">
        <legend>Авторы</legend>
        <?php foreach (Author::findAll() as $author) : ?>
            <input class="field-input" id="" type="checkbox"
                   name="authors"
                   value="<?= $author->id ?>"
                   "<?php in_array($author, $model->getAuthors()) ? 'checked' : ''?>>
            <label class="field-label"><?= $author->getShortName() ?></label>
        <?php endforeach; ?>
        <p class="field-description"></p>
    </fieldset>
    <input class="button form-submit" type="submit" value="Сохранить">
</form>
