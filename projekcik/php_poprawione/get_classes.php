<?php

$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data2.db';
$username = '';
$password = '';

use App\Model\Teacher;
use App\Model\Room;
use App\Model\Building;
use App\Model\Subject;
use App\Model\Groups;
use App\Model\Department;
use App\Model\Classes;

require_once "H:/aplikacje_plan/projekcik/src/Model/Teacher.php";
require_once "H:/aplikacje_plan/projekcik/src/Model/Room.php";
require_once "H:/aplikacje_plan/projekcik/src/Model/Building.php";
require_once "H:/aplikacje_plan/projekcik/src/Model/Subject.php";
require_once "H:/aplikacje_plan/projekcik/src/Model/Groups.php";
require_once "H:/aplikacje_plan/projekcik/src/Model/Classes.php";
require_once "H:/aplikacje_plan/projekcik/src/Model/Department.php";
require_once 'H:/aplikacje_plan/projekcik/src/Service/Config.php';

try {
    $classes = new Classes();
    $teacher = new Teacher();
    $room = new Room();
    $building = new Building();
    $subject = new Subject();
    $group = new Groups();
    $department = new Department();

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to the database!<br>";
    $classes = new Classes();
    $classes->deleteAll();
    $classes->resetAutoincrement();

    echo "The 'Classes' table has been cleared and reset.<br>";
    $sqlGetTeachers = "SELECT * FROM Teacher";
    $teachers = $pdo->query($sqlGetTeachers)->fetchAll(PDO::FETCH_ASSOC);

    // Today's date
    $today = new DateTime('now');
    $today->setTime(0, 0, 0);

    // Define the last valid date (end of February)
    $endOfFebruary = new DateTime('2025-02-28'); // Change to the end of the target year

    function findBestRoom($room, $rooms) {
        $bestMatch = null;
        $bestMatchLength = 0;

        foreach ($rooms as $roomRecord) {
            $roomName = $roomRecord['Room_Name'];
            if (strpos($room, $roomName) !== false) {
                $matchLength = strlen($roomName);
                if ($matchLength > $bestMatchLength) {
                    $bestMatch = $roomRecord;
                    $bestMatchLength = $matchLength;
                }
            }
        }
        return $bestMatch;
    }

    function findBestBuilding($room, $buildings) {
        $bestMatch = null;
        $bestMatchLength = 0;

        foreach ($buildings as $building) {
            $buildingName = $building['Building_Name'];
            if (strpos($room, $buildingName) !== false) {
                $matchLength = strlen($buildingName);
                if ($matchLength > $bestMatchLength) {
                    $bestMatch = $building;
                    $bestMatchLength = $matchLength;
                }
            }
        }
        return $bestMatch;
    }

    $sqlGetRooms = "SELECT * FROM Room";
    $rooms = $pdo->query($sqlGetRooms)->fetchAll(PDO::FETCH_ASSOC);

    $sqlGetBuildings = "SELECT * FROM Building";
    $buildings = $pdo->query($sqlGetBuildings)->fetchAll(PDO::FETCH_ASSOC);

    foreach ($teachers as $teacher) {
        $teacherName = urlencode($teacher['LastName'] . ' ' . $teacher['FirstName']);
        $url = "https://plan.zut.edu.pl/schedule_student.php?teacher=$teacherName";

        $data = file_get_contents($url);
        $lessons = json_decode($data, true);

        if (!$lessons) {
            echo "No data for teacher: " . $teacher['LastName'] . " " . $teacher['FirstName'] . "<br>";
        }

        foreach ($lessons as $lesson) {

            $startDateTime = new DateTime($lesson['start']);
            $lessonDate = clone $startDateTime;
            $lessonDate->setTime(0, 0, 0);

            // Skip lessons that are after the end of February or before today
            if ($lessonDate > $endOfFebruary) {
                echo "Ignoring class: " . $lesson['subject'] . " (date: " . $lessonDate->format('Y-m-d') . ") because it's after February 28.<br>";
                continue;
            }

            // Skip lessons that are before today
            if ($lessonDate < $today) {
                echo "Ignoring class: " . $lesson['subject'] . " (date: " . $lessonDate->format('Y-m-d') . ") because it's before today.<br>";
                continue;
            }

            $subject = $lesson['subject'];
            $sqlGetSubject = "SELECT Subject_ID FROM Subject WHERE Subject_Name = :subject";
            $stmt = $pdo->prepare($sqlGetSubject);
            $stmt->bindParam(':subject', $subject);
            $stmt->execute();
            $subjectId = $stmt->fetchColumn();

            if (!$subjectId) {
                $sqlInsertSubject = "INSERT INTO Subject (Subject_Name, Subject_Type) VALUES (:subject, :lessonForm)";
                $stmt = $pdo->prepare($sqlInsertSubject);
                $stmt->bindParam(':subject', $subject);
                $stmt->bindParam(':lessonForm', $lesson['lesson_form']);
                $stmt->execute();
                $subjectId = $pdo->lastInsertId();
            }

            $teacherId = $teacher['Teacher_ID'];

            $room = $lesson['room'];
            $bestRoom = findBestRoom($room, $rooms);
            $roomName = $bestRoom ? $bestRoom['Room_Name'] : $room;

            $bestBuilding = findBestBuilding($room, $buildings);
            $buildingId = $bestBuilding ? $bestBuilding['Building_ID'] : null;

            $groupName = $lesson['group_name'];
            $sqlGetGroup = "SELECT Group_ID FROM Groups WHERE Group_Name = :groupName";
            $stmt = $pdo->prepare($sqlGetGroup);
            $stmt->bindParam(':groupName', $groupName);
            $stmt->execute();
            $groupId = $stmt->fetchColumn();

            if (!$groupId) {
                $sqlInsertGroup = "INSERT INTO Groups (Group_Name) VALUES (:groupName)";
                $stmt = $pdo->prepare($sqlInsertGroup);
                $stmt->bindParam(':groupName', $groupName);
                $stmt->execute();
                $groupId = $pdo->lastInsertId();
            }

            $departmentPrefix = explode(' ', str_replace('_', ' ', $room))[0];
            $sqlGetDepartment = "SELECT Department_ID FROM Department WHERE Department_Name = :departmentName";
            $stmt = $pdo->prepare($sqlGetDepartment);
            $stmt->bindParam(':departmentName', $departmentPrefix);
            $stmt->execute();
            $departmentId = $stmt->fetchColumn();

            $startTime = $startDateTime->format('H:i:s');
            $endDateTime = new DateTime($lesson['end']);
            $endTime = $endDateTime->format('H:i:s');
            $dateFormatted = $lessonDate->format('Y-m-d');

            $sqlInsertLesson = "
                INSERT INTO Classes (
                    Subject_ID, Room_Name, Start, End, Teacher_ID, Building_ID, 
                    Group_ID, Department_ID, Date, Status
                ) VALUES (
                    :subjectId, :roomName, :startTime, :endTime, :teacherId, :buildingId, 
                    :groupId, :departmentId, :date, :status
                ) 
            ";
            $stmt = $pdo->prepare($sqlInsertLesson);
            $stmt->bindParam(':subjectId', $subjectId);
            $stmt->bindParam(':roomName', $roomName);
            $stmt->bindParam(':startTime', $startTime);
            $stmt->bindParam(':endTime', $endTime);
            $stmt->bindParam(':teacherId', $teacherId);
            $stmt->bindParam(':buildingId', $buildingId);
            $stmt->bindParam(':groupId', $groupId);
            $stmt->bindParam(':departmentId', $departmentId);
            $stmt->bindParam(':date', $dateFormatted);
            $stmt->bindParam(':status', $lesson['lesson_status']);
            $stmt->execute();

            echo "Added class: " . $lesson['subject'] . "<br>";
        }
    }

    echo "Process completed.<br>";
} catch (PDOException $e) {
    echo "Connection error: " . $e->getMessage();
}
?>
