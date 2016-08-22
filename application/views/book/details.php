<?php

/**
 * @var application\models\Book $model
 */
?>

<article class="book">
    <div>
        <h2><?= $model->title ?></h2>
        <a class="button" href="/book/edit/<?= $model->id ?>">Редактировать</a>
        <a class="button" href="/book/delete/<?= $model->id ?>">Удалить</a>
    </div>
    <img src="/images/<?= $model->picture ?>" alt="<?= $model->title ?>">
    <span class="book-isbn"><?= $model->isbn ?></span>
    <span class="book-pages"><?= $model->pages_amount ?></span>
    <span class="book-published"><?= $model->published ?></span>
    <ul class="book-authors">
        <?php foreach($model->getAuthors() as $author) : ?>
            <li><?= $author->getShortName() ?></li>
        <?php endforeach; ?>
    </ul>
</article>
