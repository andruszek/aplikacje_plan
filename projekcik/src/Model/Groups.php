<?php

namespace App\Model;

use App\Service\Config;

class Groups
{
    private ?int $Group_ID = null;
    private ?string $Group_Name = null;

    public function getGroup_ID(): ?int
    {
        return $this->Group_ID;
    }

    public function setGroup_ID(?int $group_id): Groups
    {
        $this->Group_ID = $group_id;
        return $this;
    }

    public function getGroup_Name(): ?string
    {
        return $this->Group_Name;
    }

    public function setGroup_Name(?string $group_name): Groups
    {
        $this->Group_Name = $group_name;
        return $this;
    }


    public static function fromArray($array): Groups
    {
        $group = new self();
        $group->fill($array);
        return $group;
    }



    public static function findByName(string $name): ?Room
    {
        $pdo = new \PDO(Config::get('db_dsn'));
        $sql = "SELECT * FROM Room WHERE Room_Name = :roomName";
        $statement = $pdo->prepare($sql);
        $statement->execute(['roomName' => $name]);

        $data = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$data) {
            return null;
        }

        $room = new self();
        $room->Room_ID = $data['Room_ID'];
        $room->Room_Name = $data['Room_Name'];

        return $room;
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "INSERT INTO Groups (Group_Name) VALUES (:Group_Name)";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'Group_Name' => $this->getGroup_Name(),
        ]);
    }


    public function deleteAll(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM Groups";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        echo "Tabela 'Groups' została opróżniona.<br>";
    }

    public function resetAutoincrement(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Groups'";
        $statement = $pdo->prepare($sqlResetAutoincrement);
        $statement->execute();
        echo "Tabela 'Groups' została zresetowana.<br>";
    }

    private function fill($array): void
    {
        if (isset($array['Group_ID'])) {
            $this->setGroup_ID($array['Group_ID']);
        }
        if (isset($array['Group_Name'])) {
            $this->setGroup_Name($array['Group_Name']);
        }
    }
}
