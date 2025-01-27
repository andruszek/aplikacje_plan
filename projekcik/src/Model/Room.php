<?php

namespace App\Model;

use App\Service\Config;

class Room
{
    private ?int $Room_ID = null;
    private ?string $Room_Name = null;
    public function getRoom_ID(): ?int
    {
        return $this->Room_ID;
    }

    public function setRoom_ID(?int $room_id): Room
    {
        $this->Room_ID = $room_id;
        return $this;
    }

    public function getRoom_Name(): ?string
    {
        return $this->Room_Name;
    }

    public function setRoom_Name(?string $room_name): Room
    {
        $this->Room_Name = $room_name;
        return $this;
    }

    public static function fromArray(array $array): Room
    {
        $room = new self();
        $room->fill($array);
        return $room;
    }

    private function fill(array $array): void
    {
        if (isset($array['Room_ID'])) {
            $this->setRoom_ID($array['Room_ID']);
        }
        if (isset($array['Room_Name'])) {
            $this->setRoom_Name($array['Room_Name']);
        }
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        if ($this->getRoom_ID() === null) {
            $sql = "INSERT INTO Room (Room_Name) VALUES (:Room_Name)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'Room_Name' => $this->getRoom_Name(),
            ]);
        } else {

            $sql = "UPDATE Room SET Room_Name = :Room_Name WHERE Room_ID = :Room_ID";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'Room_ID' => $this->getRoom_ID(),
                'Room_Name' => $this->getRoom_Name(),
            ]);
        }
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM Room WHERE Room_ID = :Room_ID";
        $statement = $pdo->prepare($sql);
        $statement->execute(['Room_ID' => $this->getRoom_ID()]);
    }

    public static function deleteAll(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM Room";
        $statement = $pdo->prepare($sql);
        $statement->execute();
    }

    public static function resetAutoincrement(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Room'";
        $statement = $pdo->prepare($sqlResetAutoincrement);
        $statement->execute();
    }

    public static function find(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "SELECT * FROM Room";
        $statement = $pdo->query($sql);
        $roomsData = $statement->fetchAll(\PDO::FETCH_ASSOC);

        $rooms = [];


        foreach ($roomsData as $roomData) {
            $rooms[] = self::fromArray($roomData);
        }

        return $rooms;
    }


    public static function findByName(string $roomName): ?Room
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Room WHERE Room_Name = :roomName';
        $statement = $pdo->prepare($sql);
        $statement->execute(['roomName' => $roomName]);

        $roomData = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$roomData) {
            return null;
        }
        return self::fromArray($roomData);
    }

    public static function findIDByName(string $roomName): ?int
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT Room_ID FROM Room WHERE Room_Name = :roomName';
        $statement = $pdo->prepare($sql);
        $statement->execute(['roomName' => $roomName]);
        $roomData = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$roomData) {
            return null;
        }

        return $roomData['Room_ID'];
    }


}
?>
