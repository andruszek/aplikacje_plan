<?php

use App\Service\Config;
use App\Model\Teacher;
use App\Model\Subject;

require_once  "H:/aplikacje_plan/projekcik/src/Model/Teacher.php";
require_once  "H:/aplikacje_plan/projekcik/src/Model/Subject.php";
require_once 'H:/aplikacje_plan/projekcik/src/Service/Config.php';

try {
    $subject = new Subject();
    $subject->deleteAll();
    $subject->resetAutoincrement();
    $teachers = Teacher::find();

    foreach ($teachers as $teacher) {
        echo "Wykładowca: " . $teacher->getFirstName() . " " . $teacher->getLastName() . "<br>";
        $workerFullName = $teacher->getLastName() . ' ' . $teacher->getFirstName();
        echo "Przetwarzam wykładowcę: $workerFullName<br>";
        $url = 'https://plan.zut.edu.pl/schedule_student.php?teacher=' . urlencode($workerFullName);
        $response = file_get_contents($url);

        if ($response === false) {
            throw new Exception("Nie udało się pobrać danych z API: $url");
        }

        $cleanedResponse = preg_replace('/^Warning.*$/m', '', $response);
        $lessons = json_decode($cleanedResponse, true);

        if ($lessons === null) {
            echo "Brak danych lub błąd podczas dekodowania odpowiedzi API dla $workerFullName.<br>";
            continue;
        }

        // Przetwarzamy dane o zajęciach
        foreach ($lessons as $lesson) {
            if (isset($lesson['subject']) && isset($lesson['lesson_form'])) {
                $subjectName = $lesson['subject'];
                $lessonForm = $lesson['lesson_form'];

                // Sprawdzamy, czy taki przedmiot o tej samej nazwie i typie już istnieje
                $existingSubject = $subject->findByNameAndType($subjectName, $lessonForm);

                if (!$existingSubject) {
                    // Jeśli przedmiot nie istnieje, dodajemy go
                    $subject->setSubject_Name($subjectName)
                        ->setSubject_Type($lessonForm)
                        ->save();
                    echo "Dodano przedmiot: $subjectName ($lessonForm)<br>";
                } else {
                    // Jeśli przedmiot już istnieje, pomijamy go
                    echo "Przedmiot już istnieje: $subjectName ($lessonForm)<br>";
                }
            } else {
                echo "Brak danych dla przedmiotu lub formy zajęć w odpowiedzi API.<br>";
            }
        }
    }

    echo "Operacja zakończona pomyślnie!";
} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
} catch (Exception $e) {
    echo "Błąd: " . $e->getMessage();
}

?>
