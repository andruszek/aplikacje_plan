<?php

$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';
$username = '';
$password = '';

try {

    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Połączono z bazą danych!<br>";

    $sqlDeleteLessons = "DELETE FROM Zajecia";
    $pdo->exec($sqlDeleteLessons);

    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Zajecia'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Zajecia' została opróżniona i zresetowana.<br>";

    // Pobierz listę wykładowców
    $sqlGetTeachers = "SELECT * FROM Wykladowcy";
    $teachers = $pdo->query($sqlGetTeachers)->fetchAll(PDO::FETCH_ASSOC);

    if (!$teachers) {
        echo "Brak wykładowców w tabeli Wykladowcy.<br>";
        exit;
    }

    // Dzisiejsza data
    $today = new DateTime('now');
    $today->setTime(0, 0, 0);


    function findBestRoom($room, $rooms) {
        $bestMatch = null;
        $bestMatchLength = 0;

        foreach ($rooms as $roomRecord) {
            $roomName = $roomRecord['Nazwa_Sali'];
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
            $buildingName = $building['Nazwa_Budynku'];
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


    $sqlGetRooms = "SELECT * FROM Sala";
    $rooms = $pdo->query($sqlGetRooms)->fetchAll(PDO::FETCH_ASSOC);


    $sqlGetBuildings = "SELECT * FROM Budynek";
    $buildings = $pdo->query($sqlGetBuildings)->fetchAll(PDO::FETCH_ASSOC);


    foreach ($teachers as $teacher) {
        $teacherName = urlencode($teacher['Nazwisko'] . ' ' . $teacher['Imie']);
        $url = "https://plan.zut.edu.pl/schedule_student.php?teacher=$teacherName";


        $data = file_get_contents($url);
        $lessons = json_decode($data, true);

        if (!$lessons) {
            echo "Brak danych dla wykładowcy: " . $teacher['Nazwisko'] . " " . $teacher['Imie'] . "<br>";
            continue;
        }


        foreach ($lessons as $lesson) {

            $startDateTime = new DateTime($lesson['start']);
            $lessonDate = clone $startDateTime;
            $lessonDate->setTime(0, 0, 0);

            if ($lessonDate < $today) {
                echo "Ignoruję zajęcia: " . $lesson['subject'] . " (data: " . $lessonDate->format('Y-m-d') . ")<br>";
                continue;
            }

            $subject = $lesson['subject'];
            $sqlGetSubject = "SELECT ID_Przedmiotu FROM Przedmioty WHERE Nazwa_Przedmiotu = :subject";
            $stmt = $pdo->prepare($sqlGetSubject);
            $stmt->bindParam(':subject', $subject);
            $stmt->execute();
            $subjectId = $stmt->fetchColumn();

            if (!$subjectId) {
                $sqlInsertSubject = "INSERT INTO Przedmioty (Nazwa_Przedmiotu, Typ_Zajec) VALUES (:subject, :lessonForm)";
                $stmt = $pdo->prepare($sqlInsertSubject);
                $stmt->bindParam(':subject', $subject);
                $stmt->bindParam(':lessonForm', $lesson['lesson_form']);
                $stmt->execute();
                $subjectId = $pdo->lastInsertId();
            }

            $teacherId = $teacher['ID_Wykladowcy'];

            $room = $lesson['room'];
            $bestRoom = findBestRoom($room, $rooms);
            $roomName = $bestRoom ? $bestRoom['Nazwa_Sali'] : $room;

            $bestBuilding = findBestBuilding($room, $buildings);
            $buildingId = $bestBuilding ? $bestBuilding['ID_Budynku'] : null;

            $groupName = $lesson['group_name'];
            $sqlGetGroup = "SELECT ID_Grupy FROM Grupa WHERE Nazwa_Grupy = :groupName";
            $stmt = $pdo->prepare($sqlGetGroup);
            $stmt->bindParam(':groupName', $groupName);
            $stmt->execute();
            $groupId = $stmt->fetchColumn();

            if (!$groupId) {
                $sqlInsertGroup = "INSERT INTO Grupa (Nazwa_Grupy) VALUES (:groupName)";
                $stmt = $pdo->prepare($sqlInsertGroup);
                $stmt->bindParam(':groupName', $groupName);
                $stmt->execute();
                $groupId = $pdo->lastInsertId();
            }


            $departmentPrefix = explode(' ', str_replace('_', ' ', $room))[0];
            $sqlGetDepartment = "SELECT ID_Wydzialu FROM Wydzial WHERE Nazwa_Wydzialu = :departmentName";
            $stmt = $pdo->prepare($sqlGetDepartment);
            $stmt->bindParam(':departmentName', $departmentPrefix);
            $stmt->execute();
            $departmentId = $stmt->fetchColumn();


            $startTime = $startDateTime->format('H:i:s');
            $endDateTime = new DateTime($lesson['end']);
            $endTime = $endDateTime->format('H:i:s');
            $dateFormatted = $lessonDate->format('Y-m-d');


            $sqlInsertLesson = "
                INSERT INTO Zajecia (
                    ID_Przedmiotu, Sala, Godzina_Startu, Godzina_Konca, ID_Wykladowcy, ID_Budynku, 
                    ID_Grupy, ID_Wydzialu, Data, Status
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

            echo "Dodano zajęcia: " . $lesson['subject'] . "<br>";
        }
    }

    echo "Proces zakończony.<br>";
} catch (PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
