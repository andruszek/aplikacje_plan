<?php
// Dane połączenia do bazy danych
$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';
$username = '';
$password = '';

// URL do pobrania danych
$url = 'https://plan.zut.edu.pl/schedule.php?kind=room&query=';

// Próba połączenia z bazą danych
try {
    // Tworzymy obiekt PDO
    $pdo = new PDO($dsn, $username, $password);

    // Ustawiamy tryb błędów na wyjątek
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Połączono z bazą danych!<br>";

    // 1. Opróżniamy tabelę z danych
    $sqlDelete = "DELETE FROM Wydzial";
    $pdo->exec($sqlDelete);

    // Resetujemy wartość AUTOINCREMENT
    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Wydzial'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Wydzial' została opróżniona i zresetowana.<br>";

    // 2. Pobieramy dane z URL
    $data = file_get_contents($url);

    $rooms = json_decode($data, true);
    foreach ($rooms as $room) {
        try {
            $item = $room['item'];

            // Usuwamy wszystko po pierwszym podkreśleniu lub spacji
            $departmentName = strtok($item, ' _');  // strtok pozwala wyciąć część przed ' ' lub '_'

            // Sprawdzamy, czy wydział już istnieje w bazie danych
            $sqlCheck = "SELECT COUNT(*) FROM Wydzial WHERE Nazwa_Wydzialu = :departmentName";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':departmentName', $departmentName);
            $stmtCheck->execute();
            $count = $stmtCheck->fetchColumn();

            // Jeśli wydział nie istnieje, wstawiamy go do tabeli
            if ($count == 0) {
                $sqlInsert = "INSERT INTO Wydzial (Nazwa_Wydzialu) VALUES (:departmentName)";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->bindParam(':departmentName', $departmentName);
                $stmtInsert->execute();
                echo "Wstawiono wydział: $departmentName<br>";
            } else {
                // Możesz dodać dodatkowe logi lub działania w przypadku, gdy wydział już istnieje
                echo "Wydział $departmentName już istnieje.<br>";
            }
        } catch (PDOException $e) {
            echo "Błąd podczas wstawiania danych: " . $e->getMessage() . "<br>";
        }
    }
} catch (PDOException $e) {
    // Obsługa błędów
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
