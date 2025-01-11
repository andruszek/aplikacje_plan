<?php
namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Games;
use App\Service\Router;
use App\Service\Templating;

class GamesController
{
    public function indexAction(Templating $templating, Router $router): ?string
    {
        $games = Games::findAll();
        $html = $templating->render('games/index.html.php', [
            'games' => $games,
            'router' => $router,
        ]);
        return $html;
    }

    public function createAction(?array $requestGames, Templating $templating, Router $router): ?string
    {
        if ($requestGames) {
            $game = Games::fromArray($requestGames);
            // @todo missing validation
            $game->save();

            $path = $router->generatePath('games-index');
            $router->redirect($path);
            return null;
        } else {
            $game = new Games();
        }

        $html = $templating->render('games/create.html.php', [
            'game' => $game,
            'router' => $router,
        ]);
        return $html;
    }

    public function editAction(int $gameId, ?array $requestGame, Templating $templating, Router $router): ?string
    {
        $game = Games::find($gameId);
        if (! $game) {
            throw new NotFoundException("Missing game with id $gameId");
        }

        if ($requestGame) {
            $game->fill($requestGame);
            // @todo missing validation
            $game->save();

            $path = $router->generatePath('games-index');
            $router->redirect($path);
            return null;
        }

        $html = $templating->render('games/edit.html.php', [
            'game' => $game,
            'router' => $router,
        ]);
        return $html;
    }

    public function showAction(int $gameId, Templating $templating, Router $router): ?string
    {
        $game = Games::find($gameId);
        if (! $game) {
            throw new NotFoundException("Missing post with id $gameId");
        }

        $html = $templating->render('games/show.html.php', [
            'game' => $game,
            'router' => $router,
        ]);
        return $html;
    }

    public function deleteAction(int $gameId, Router $router): ?string
    {
        $game = Games::find($gameId);
        if (! $game) {
            throw new NotFoundException("Missing game with id $game");
        }

        $game->delete();
        $path = $router->generatePath('games-index');
        $router->redirect($path);
        return null;
    }
}
