<?php
/**
 * @var array $books
 * @var application\models\Book $book
 */
?>

<section class="books">
    <?php foreach ($books as $book) : ?>
        <article class="book">
            <img class="book-picture" src="images/<?= $book->picture ?>" alt="<?= $book->title ?>">
            <div class="book-information">
                <a href="book/view/<?= $book->id ?>"><h2><?= $book->title ?></h2></a>
                <div class="isbn">ISBN - <?= $book->isbn ?></div>
                <div class="pages">Толщина книги - <?= $book->pages_amount ?> страниц</div>
                <div class="published"><?= $book->published ?></div>
            </div>
        </article>
    <?php endforeach; ?>
</section>

<aside class="actions">
    <a class="button" href="/book/edit">Добавить книгу</a>
</aside>
