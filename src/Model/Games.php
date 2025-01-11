<?php
namespace App\Model;

use App\Service\Config;

class Games
{
    private ?int $id = null;
    private ?string $name = null;
    private ?string $rating = null;
    private? string $info = null;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): Games
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): Games
    {
        $this->name = $name;

        return $this;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function setRating(?string $rating): Games
    {
        $this->rating = $rating;

        return $this;
    }

    public function getInfo(): ?string
    {
        return $this->info;
    }

    public function setInfo(?string $info): Games
    {
        $this->info = $info;

        return $this;
    }




    public static function fromArray($array): Games
    {
        $post = new self();
        $post->fill($array);

        return $post;
    }

    public function fill($array): Games
    {
        if (isset($array['id']) && ! $this->getId()) {
            $this->setId($array['id']);
        }
        if (isset($array['name'])) {
            $this->setName($array['name']);
        }
        if (isset($array['rating'])) {
            $this->setRating($array['rating']);
        }
        if (isset($array['info'])) {
            $this->setInfo($array['info']);
        }

        return $this;
    }

    public static function findAll(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM games';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $games = [];
        $gamesArray = $statement->fetchAll(\PDO::FETCH_ASSOC);
        foreach ($gamesArray as $gameArray) {
            $games[] = self::fromArray($gameArray);
        }

        return $games;
    }

    public static function find($id): ?Games
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM games WHERE id = :id';
        $statement = $pdo->prepare($sql);
        $statement->execute(['id' => $id]);

        $gameArray = $statement->fetch(\PDO::FETCH_ASSOC);
        if (! $gameArray) {
            return null;
        }
        $game = Games::fromArray($gameArray);

        return $game;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if (! $this->getId()) {
            $sql = "INSERT INTO games (name, rating, info) VALUES (:name, :rating, :info)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'name' => $this->getName(),
                'rating' => $this->getRating(),
                'info' => $this->getInfo(),
            ]);

            $this->setId($pdo->lastInsertId());
        } else {
            $sql = "UPDATE games SET name = :name, rating = :rating, info = :info WHERE id = :id";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                ':name' => $this->getName(),
                ':price' => $this->getRating(),
                ':info' => $this->getInfo(),
                ':id' => $this->getId(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM games WHERE id = :id";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            ':id' => $this->getId(),
        ]);

        $this->setId(null);
        $this->setName(null);
        $this->setRating(null);
        $this->setInfo(null);
    }
}
