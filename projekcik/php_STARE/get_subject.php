<?php

$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';

try {

    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Połączono z bazą danych!<br>";


    $sqlDelete = "DELETE FROM Przedmioty";
    $pdo->exec($sqlDelete);


    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Przedmioty'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Przedmioty' została opróżniona, a licznik AUTOINCREMENT zresetowany.<br>";


    $sql = "SELECT Imie, Nazwisko FROM Wykladowcy";
    $stmt = $pdo->query($sql);


    $wykladowcy = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($wykladowcy as $wykladowca) {

        $workerFullName = $wykladowca['Nazwisko'] . ' ' . $wykladowca['Imie'];
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

        // Przechodzimy przez wszystkie lekcje i zapisujemy unikalne przedmioty oraz formy zajęć
        foreach ($lessons as $lesson) {

            if (isset($lesson['subject']) && isset($lesson['lesson_form'])) {
                $subject = $lesson['subject'];
                $lessonForm = $lesson['lesson_form'];


                $sqlCheck = "SELECT COUNT(*) FROM Przedmioty WHERE Nazwa_Przedmiotu = :subject";
                $stmtCheck = $pdo->prepare($sqlCheck);
                $stmtCheck->bindParam(':subject', $subject);
                $stmtCheck->execute();


                if ($stmtCheck->fetchColumn() == 0) {
                    $sqlInsert = "INSERT INTO Przedmioty (Nazwa_Przedmiotu, Typ_Zajec) VALUES (:subject, :lessonForm)";
                    $stmtInsert = $pdo->prepare($sqlInsert);
                    $stmtInsert->bindParam(':subject', $subject);
                    $stmtInsert->bindParam(':lessonForm', $lessonForm);
                    $stmtInsert->execute();
                    echo "Dodano przedmiot: $subject ($lessonForm)<br>";
                } else {
                    echo "Przedmiot już istnieje: $subject ($lessonForm)<br>";
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
