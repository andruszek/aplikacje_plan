<?php

namespace App\Model;

use App\Service\Config;

class Subject
{
    private ?int $Subject_ID = null;
    private ?string $Subject_Name = null;
    private ?string $Subject_Type = null;

    public function getSubject_ID(): ?int
    {
        return $this->Subject_ID;
    }

    public function setSubject_ID(?int $subject_id): Subject
    {
        $this->Subject_ID = $subject_id;
        return $this;
    }

    public function getSubject_Name(): ?string
    {
        return $this->Subject_Name;
    }

    public function setSubject_Name(?string $subject_name): Subject
    {
        $this->Subject_Name = $subject_name;
        return $this;
    }

    public function getSubject_Type(): ?string
    {
        return $this->Subject_Type;
    }

    public function setSubject_Type(?string $subject_type): Subject
    {
        $this->Subject_Type = $subject_type;
        return $this;
    }

    public static function fromArray($array): Subject
    {
        $subject = new self();
        $subject->fill($array);
        return $subject;
    }

    private function fill($array): void
    {
        if (isset($array['Subject_ID'])) {
            $this->setSubject_ID($array['Subject_ID']);
        }
        if (isset($array['Subject_Name'])) {
            $this->setSubject_Name($array['Subject_Name']);
        }
        if (isset($array['Subject_Type'])) {
            $this->setSubject_Type($array['Subject_Type']);
        }
    }

    public function save(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));

        // Zmieniamy logikę zapisu
        $existingSubject = self::findByNameAndType($this->getSubject_Name(), $this->getSubject_Type());

        if ($existingSubject) {
            // Jeśli przedmiot istnieje, nie zapisujemy go ponownie
            echo "Przedmiot już istnieje: " . $this->getSubject_Name() . " (" . $this->getSubject_Type() . ")<br>";
            return;
        }

        // Jeśli przedmiot nie istnieje, zapisujemy go
        if ($this->getSubject_ID() === null) {
            $sql = "INSERT INTO Subject (Subject_Name, Subject_Type) VALUES (:Subject_Name, :Subject_Type)";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'Subject_Name' => $this->getSubject_Name(),
                'Subject_Type' => $this->getSubject_Type(),
            ]);
        } else {
            $sql = "UPDATE Subject SET Subject_Name = :Subject_Name, Subject_Type = :Subject_Type WHERE Subject_ID = :Subject_ID";
            $statement = $pdo->prepare($sql);
            $statement->execute([
                'Subject_ID' => $this->getSubject_ID(),
                'Subject_Name' => $this->getSubject_Name(),
                'Subject_Type' => $this->getSubject_Type(),
            ]);
        }
    }

    public static function findByNameAndType(string $subjectName, string $subjectType): ?Subject
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Subject WHERE Subject_Name = :subjectName AND Subject_Type = :subjectType';
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'subjectName' => $subjectName,
            'subjectType' => $subjectType
        ]);

        $subjectData = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$subjectData) {
            return null;
        }

        return self::fromArray($subjectData);
    }

    public function delete(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM Subject WHERE Subject_ID = :Subject_ID";
        $statement = $pdo->prepare($sql);
        $statement->execute(['Subject_ID' => $this->getSubject_ID()]);
    }

    public static function findByName(string $subjectName): ?Subject
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Subject WHERE Subject_Name = :subjectName';
        $statement = $pdo->prepare($sql);
        $statement->execute(['subjectName' => $subjectName]);

        $subjectData = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$subjectData) {
            return null;
        }

        return self::fromArray($subjectData);
    }



    public static function deleteAll(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = "DELETE FROM Subject";
        $statement = $pdo->prepare($sql);
        $statement->execute();
    }

    public static function resetAutoincrement(): void
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Subject'";
        $statement = $pdo->prepare($sqlResetAutoincrement);
        $statement->execute();
    }

    public static function find(): array
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT * FROM Subject';
        $statement = $pdo->prepare($sql);
        $statement->execute();

        $subjectsData = $statement->fetchAll(\PDO::FETCH_ASSOC);
        $subjects = [];
        foreach ($subjectsData as $subjectData) {
            $subjects[] = self::fromArray($subjectData);
        }
        return $subjects;
    }


    public static function findIDByName(string $subjectName): ?int
    {
        $pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));
        $sql = 'SELECT Subject_ID FROM Subject WHERE Subject_Name = :subjectName';
        $statement = $pdo->prepare($sql);
        $statement->execute(['subjectName' => $subjectName]);

        $subjectData = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$subjectData) {
            return null;
        }


        return $subjectData['Subject_ID'];
    }


}
?>
