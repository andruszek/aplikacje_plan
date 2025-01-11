<?php

/** @var \App\Model\Games $game */
/** @var \App\Service\Router $router */

$title = "{$game->getName()} ({$game->getId()})";
$bodyClass = 'show';

ob_start(); ?>
    <h1><?= $game->getName() ?></h1>
    <h2><?= $game->getRating() ?></h2>
    <article>
        <?= $game->getInfo();?>
    </article>

    <ul class="action-list">
        <li> <a href="<?= $router->generatePath('games-index') ?>">Back to list</a></li>
        <li><a href="<?= $router->generatePath('games-edit', ['id'=> $game->getId()]) ?>">Edit</a></li>
    </ul>
<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
