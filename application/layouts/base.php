<?php

/**
 * @var string $page_title
 * @var string $view
 */
?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="/css/styles.css">
    <meta charset="utf-8">
    <title><?= $page_title ?></title>
</head>
<body>
    <main>
        <?php include $view ?>
    </main>
</body>
</html>
