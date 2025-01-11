<?php

/** @var \App\Model\Games[] $games */
/** @var \App\Service\Router $router */

$title = 'Games List';
$bodyClass = 'index';

ob_start(); ?>
    <h1>Games List</h1>

    <a href="<?= $router->generatePath('games-create') ?>">Create new</a>

    <ul class="index-list">
        <?php foreach ($games as $game): ?>
            <li><h3><?= $game->getName() ?></h3>
                <ul class="action-list">
                    <li><a href="<?= $router->generatePath('games-show', ['id' => $game->getId()]) ?>">Details</a></li>
                    <li><a href="<?= $router->generatePath('games-edit', ['id' => $game->getId()]) ?>">Edit</a></li>
                </ul>
            </li>
        <?php endforeach; ?>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
