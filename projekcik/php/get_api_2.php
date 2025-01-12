<?php
// Dane połączenia do bazy danych
$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';
$username = '';
$password = '';

// URL do pobrania danych
$url = 'https://plan.zut.edu.pl/schedule.php?kind=room&query=';

// Lista wyjątków, które muszą mieć nazwę budynku "1"
$exceptions = ['1_WBiHZ'];

// Próba połączenia z bazą danych
try {
    // Tworzymy obiekt PDO
    $pdo = new PDO($dsn, $username, $password);

    // Ustawiamy tryb błędów na wyjątek
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Połączono z bazą danych!<br>";

    // 1. Opróżniamy tabelę z danych
    $sqlDelete = "DELETE FROM Budynek";
    $pdo->exec($sqlDelete);

    // Resetujemy wartość AUTOINCREMENT
    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Budynek'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Budynek' została opróżniona i zresetowana.<br>";

    // 2. Pobieramy dane z URL
    $data = file_get_contents($url);
    $rooms = json_decode($data, true);

    // 3. Przechodzimy przez każdy rekord
    foreach ($rooms as $room) {
        // Pobieramy cały zapis z klucza 'item'
        $item = $room['item'];

        // Sprawdzamy, czy rekord znajduje się w wyjątkach
        if (in_array($item, $exceptions)) {
            $buildingName = '1'; // Ustawiamy nazwę budynku na '1' w przypadku wyjątku
        } else {
            // Rozdzielamy rekord na dwie części: przed i po spacji lub podkreśleniu
            $nameParts = explode(' ', $item);

            if (count($nameParts) == 2) {
                // Jeśli rekord ma 2 części, to używamy pierwszego członu jako nazwy budynku
                $buildingName = $nameParts[1];
            } else {
                // Jeśli rekord ma więcej niż 2 części, używamy drugiego członu jako nazwy budynku
                $buildingName = $nameParts[1];
            }
        }

        // Sprawdzamy, czy budynek już istnieje w bazie danych, aby uniknąć duplikatów
        $sqlCheck = "SELECT COUNT(*) FROM Budynek WHERE Nazwa_Budynku = :buildingName";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->bindParam(':buildingName', $buildingName);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

        // Jeśli budynek nie istnieje, wstawiamy go do tabeli
        if ($count == 0) {
            $sql = "INSERT INTO Budynek (Nazwa_Budynku) VALUES (:buildingName)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':buildingName', $buildingName);
            $stmt->execute();
            echo "Wstawiono budynek: $buildingName<br>";
        } else {
            echo "Budynek $buildingName już istnieje w tabeli.<br>";
        }
    }

    echo "Dane zostały zaimportowane.<br>";

} catch (PDOException $e) {
    // Obsługa błędów
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
