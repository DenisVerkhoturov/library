<?php
/**
 * @var array $models
 * @var application\models\Book $model
 */
?>

<section class="books">
    <?php foreach ($models as $model) : ?>
        <article class="book">
            <img class="book-picture" src="images/<?= $model->picture ?>" alt="<?= $model->title ?>">
            <div class="book-information">
                <a href="book/view/<?= $model->id ?>"><h2><?= $model->title ?></h2></a>
                <div class="isbn">ISBN - <?= $model->isbn ?></div>
                <div class="pages">Толщина книги - <?= $model->pages_amount ?> страниц</div>
                <div class="published"><?= $model->published ?></div>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<aside class="actions">
    <a class="button" href="/book/edit">Добавить книгу</a>
</aside>
