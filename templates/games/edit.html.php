<?php

/** @var \App\Model\Games $game */
/** @var \App\Service\Router $router */

$title = "Edit Post {$game->getName()} ({$game->getId()})";
$bodyClass = "edit";

ob_start(); ?>
    <h1><?= $title ?></h1>
    <form action="<?= $router->generatePath('games-edit') ?>" method="post" class="edit-form">
        <?php require __DIR__ . DIRECTORY_SEPARATOR . '_form.html.php'; ?>
        <input type="hidden" name="action" value="games-edit">
        <input type="hidden" name="id" value="<?= $game->getId() ?>">
    </form>

    <ul class="action-list">
        <li>
            <a href="<?= $router->generatePath('games-index') ?>">Back to list</a></li>
        <li>
            <form action="<?= $router->generatePath('games-delete') ?>" method="post">
                <input type="submit" value="Delete" onclick="return confirm('Are you sure?')">
                <input type="hidden" name="action" value="games-delete">
                <input type="hidden" name="id" value="<?= $game->getId() ?>">
            </form>
        </li>
    </ul>

<?php $main = ob_get_clean();

include __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'base.html.php';
