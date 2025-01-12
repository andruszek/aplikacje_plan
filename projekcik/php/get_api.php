<?php
// Dane połączenia do bazy danych
$dsn = 'sqlite:C:\Users\kopan\OneDrive\Desktop\judys\aplikacje_plan\projekcik\php\sql\data.db';
$username = '';
$password = '';

// URL do pobrania danych
$url = 'https://plan.zut.edu.pl/schedule.php?kind=teacher&query=';

// Próba połączenia z bazą danych
try {
    // Tworzymy obiekt PDO
    $pdo = new PDO($dsn, $username, $password);

    // Ustawiamy tryb błędów na wyjątek
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Połączono z bazą danych!<br>";

    // 1. Opróżniamy tabelę z danych
    $sqlDelete = "DELETE FROM Wykladowcy";
    $pdo->exec($sqlDelete);
    echo "Tabela 'Wykladowcy' została opróżniona.<br>";

    // 2. Pobieramy dane z URL
    $data = file_get_contents($url);
    $teachers = json_decode($data, true);

    // 3. Przechodzimy przez każdego wykładowcę
    // 3. Przechodzimy przez każdego wykładowcę
    foreach ($teachers as $teacher) {
        // Rozdzielamy imię i nazwisko
        $fullName = $teacher['item'];
        $nameParts = explode(' ', $fullName);

        // Sprawdzamy, czy mamy więcej niż jedną część (czyli więcej niż jedno słowo)
        if (count($nameParts) > 1) {
            // Ostatnia część to imię
            $firstName = array_pop($nameParts);  // Ostatnia część to imię

            // Pozostałe części to nazwisko
            $lastName = implode(' ', $nameParts);  // Łączymy pozostałe części jako nazwisko
        } else {
            // Jeśli tylko jedno słowo, traktujemy to jako imię, a nazwisko pozostaje puste
            $firstName = $nameParts[0];
            $lastName = '';
        }

        // Przygotowujemy zapytanie do bazy danych
        $sql = "INSERT INTO Wykladowcy (Imie, Nazwisko) VALUES (:firstName, :lastName)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);  // Imię jako Imie
        $stmt->bindParam(':lastName', $lastName);    // Nazwisko jako Nazwisko

        // Wykonujemy zapytanie
        $stmt->execute();
    }


} catch (PDOException $e) {
    // Obsługa błędów
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
