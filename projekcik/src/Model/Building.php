<?php

namespace App\Model;

use App\Service\Config;

class Building
{
    private ?int $Building_ID = null;
    private ?string $Building_Name = null;

    public function getBuilding_ID(): ?int
    {
        return $this->Building_ID;
    }

    public function setBuilding_ID(?int $building_id): Building
    {
        $this->Building_ID = $building_id;
        return $this;
    }

    public function getBuilding_Name(): ?string
    {
        return $this->Building_Name;
    }

    public function setBuilding_Name(?string $building_name): Building
    {
        $this->Building_Name = $building_name;
        return $this;
    }

    public static function fromArray($array): Building
    {
        $building = new self();
        $building->fill($array);
        return $building;
    }


    public static function findByName($buildingName): ?Building
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Building WHERE Building_Name = :buildingName';
        $statement = $pdo->prepare($sql);
        $statement->execute(['buildingName' => $buildingName]);

        $buildingData = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$buildingData) {
            return null;
        }
        return self::fromArray($buildingData);
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "INSERT INTO Building (Building_ID, Building_Name) VALUES (:Building_ID, :Building_Name)";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'Building_ID' => $this->getBuilding_ID(),
            'Building_Name' => $this->getBuilding_Name(),
        ]);
    }

    public function deleteAll(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM Building";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        echo "Tabela 'Building' została opróżniona.<br>";
    }

    public function resetAutoincrement(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Building'";
        $statement = $pdo->prepare($sqlResetAutoincrement);
        $statement->execute();
        echo "Tabela 'Building' została zresetowana.<br>";
    }

    private function fill($array): void
    {
        if (isset($array['Building_ID'])) {
            $this->setBuilding_ID($array['Building_ID']);
        }
        if (isset($array['Building_Name'])) {
            $this->setBuilding_Name($array['Building_Name']);
        }
    }

}