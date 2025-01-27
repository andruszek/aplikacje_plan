<?php

namespace App\Model;

use App\Service\Config;

class Teacher
{
    private ?int $Teacher_ID = null;
    private ?string $FirstName = null;
    private ?string $LastName = null;


    public function getTeacher_ID(): ?int
    {
        return $this->Teacher_ID;
    }

    public function setTeacher_ID(?int $teacher_id): Teacher
    {
        $this->Teacher_ID = $teacher_id;
        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->FirstName;
    }

    public function setFirstName(?string $first_name): Teacher
    {
        $this->FirstName = $first_name;
        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->LastName;
    }

    public function setLastName(?string $last_name): Teacher
    {
        $this->LastName = $last_name;
        return $this;
    }

    public function fill(array $data): void
    {
        if (isset($data['Teacher_ID'])) {
            $this->setTeacher_ID($data['Teacher_ID']);
        }
        if (isset($data['FirstName'])) {
            $this->setFirstName($data['FirstName']);
        }
        if (isset($data['LastName'])) {
            $this->setLastName($data['LastName']);
        }
    }

    public static function fromArray(array $array): Teacher
    {
        $teacher = new self();
        $teacher->fill($array);
        return $teacher;
    }

    public static function find(): array
    {

        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "SELECT Teacher_ID, FirstName, LastName FROM Teacher";
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        $teachers = [];

        foreach ($results as $row) {
            $teacher = self::fromArray($row);
            $teachers[] = $teacher;
        }


        return $teachers;
    }

    // Metoda save
    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "INSERT INTO Teacher (Teacher_ID, FirstName, LastName) VALUES (:Teacher_ID, :FirstName, :LastName)";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'Teacher_ID' => $this->getTeacher_ID(),
            'FirstName' => $this->getFirstName(),
            'LastName' => $this->getLastName(),
        ]);
    }

    public static function findIDByName(string $firstName, string $lastName): ?int
    {

        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT Teacher_ID FROM Teacher WHERE FirstName = :firstName AND LastName = :lastName';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['firstName' => $firstName, 'lastName' => $lastName]);
        $teacherData = $stmt->fetch(\PDO::FETCH_ASSOC);
        if (!$teacherData) {
            return null;
        }

        return $teacherData['Teacher_ID'];
    }

    public function deleteAll(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM Teacher";
        $statement = $pdo->prepare($sql);
        $statement->execute();
        echo "Tabela 'Teacher' została opróźniona.<br>";
    }

    public function resetAutoincrement(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Teacher'";
        $statement = $pdo->prepare($sqlResetAutoincrement);
        $statement->execute();
        echo "Tabela 'Teacher' została zresetowana.<br>";
    }
}
