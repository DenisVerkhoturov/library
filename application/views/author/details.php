<?php

/**
 * @var application\models\Author $model
 */
?>

<article class="author">
    <div>
        <h2><?= $model->getShortName() ?></h2>
        <a class="button" href="/author/edit/<?= $model->id ?>">Редактировать</a>
        <a class="button" href="/author/delete/<?= $model->id ?>">Удалить</a>
    </div>
    <span class="author-published"><?= $model->first_name ?></span>
    <span class="author-published"><?= $model->last_name ?></span>
    <span class="author-published"><?= $model->middle_name ?></span>
    <ul class="author-books">
        <?php foreach($model->getBooks() as $book) : ?>
            <li><?= $book->title ?></li>
        <?php endforeach; ?>
    </ul>
</article>
