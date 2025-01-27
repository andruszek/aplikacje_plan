<?php

namespace App\Model;

use App\Service\Config;
use PDO;

class Classes
{
    private $Classes_ID;
    private $Subject_ID;
    private $Room_Name;
    private $Start;
    private $End;
    private $Teacher_ID;
    private $Building_ID;
    private $Group_ID;
    private $Department_ID;
    private $Date;
    private $Status;


    public function setSubject_ID(?int $Subject_ID)
    {
        $this->Subject_ID = $Subject_ID;
    }

    public function setRoom_Name(?string $Room_Name)
    {
        $this->Room_Name = $Room_Name;
    }

    public function setStart(string $Start)
    {
        $this->Start = $Start;
    }

    public function setEnd(string $End)
    {
        $this->End = $End;
    }

    public function setTeacher_ID(?int $Teacher_ID)
    {
        $this->Teacher_ID = $Teacher_ID;
    }

    public function setBuilding_ID(?int $Building_ID)
    {
        if ($Building_ID === null) {
            $this->Building_ID = 0;
        } else {
            $this->Building_ID = $Building_ID;
        }
    }

    public function setGroup_ID(?int $Group_ID)
    {
        $this->Group_ID = $Group_ID;
    }

    public function setDepartment_ID(?int $Department_ID)
    {
        $this->Department_ID = $Department_ID;
    }

    public function setDate(string $Date)
    {
        $this->Date = $Date;
    }

    public function setStatus(?string $Status)
    {
        $this->Status = $Status;
    }

    // Save the current object to the database
    public function save()
    {
        $pdo = new PDO(Config::get('db_dsn'));
        $sql = "INSERT INTO Classes (
                    Subject_ID, Room_Name, Start, End, Teacher_ID, Building_ID, Group_ID, Department_ID, Date, Status
                ) VALUES (
                    :Subject_ID, :Room_Name, :Start, :End, :Teacher_ID, :Building_ID, :Group_ID, :Department_ID, :Date, :Status
                )";
        $statement = $pdo->prepare($sql);
        $statement->execute([
            'Subject_ID' => $this->Subject_ID,
            'Room_Name' => $this->Room_Name,
            'Start' => $this->Start,
            'End' => $this->End,
            'Teacher_ID' => $this->Teacher_ID,
            'Building_ID' => $this->Building_ID,
            'Group_ID' => $this->Group_ID,
            'Department_ID' => $this->Department_ID,
            'Date' => $this->Date,
            'Status' => $this->Status,
        ]);
    }






    // Delete all classes
    public static function deleteAll()
    {
        $pdo = new PDO(Config::get('db_dsn'));
        $pdo->exec("DELETE FROM Classes");
        echo "Tabela 'Classes' została opróżniona.<br>";
    }

    // Reset AUTOINCREMENT
    public static function resetAutoincrement()
    {
        $pdo = new PDO(Config::get('db_dsn'));
        $pdo->exec("DELETE FROM sqlite_sequence WHERE name='Classes'");
        echo "Licznik AUTOINCREMENT został zresetowany.<br>";
    }
}

?>
