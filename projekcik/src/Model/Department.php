<?php

namespace App\Model;

use App\Service\Config;

class Department
{
    private ?int $Department_ID = null;
    private ?string $Department_Name = null;

    public function getDepartment_ID(): ?int
    {
        return $this->Department_ID;
    }

    public function setDepartment_ID(?int $department_id): Department
    {
        $this->Department_ID = $department_id;
        return $this;
    }

    public function getDepartment_Name(): ?string
    {
        return $this->Department_Name;
    }

    public function setDepartment_Name(?string $department_name): Department
    {
        $this->Department_Name = $department_name;
        return $this;
    }

    public static function fromArray($array): Department
    {
        $department = new self();
        $department->fill($array);
        return $department;
    }



    public static function findByName($departmentName): ?Department
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Department WHERE Department_Name = :departmentName';
        $statement = $pdo->prepare($sql);
        $statement->execute(['departmentName' => $departmentName]);

        $departmentData = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$departmentData) {
            return null;
        }
        return self::fromArray($departmentData);
    }



    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "INSERT INTO Department (Department_ID, Department_Name) VALUES (:Department_ID, :Department_Name)";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'Department_ID' => $this->getDepartment_ID(),
            'Department_Name' => $this->getDepartment_Name(),
        ]);
    }


    public function deleteAll(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM Department";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        echo "Tabela 'Department' została opróżniona.<br>";
    }


    public function resetAutoincrement(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Department'";
        $statement = $pdo->prepare($sqlResetAutoincrement);
        $statement->execute();
        echo "Tabela 'Department' została zresetowana.<br>";
    }


    private function fill($array): void
    {
        if (isset($array['Department_ID'])) {
            $this->setDepartment_ID($array['Department_ID']);
        }
        if (isset($array['Department_Name'])) {
            $this->setDepartment_Name($array['Department_Name']);
        }
    }


}
