<?php
// Dane połączenia do bazy danych
$dsn = 'sqlite:I:/sem5/aplikacje_plan/projekcik/php/sql/data.db';
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

    // Resetujemy wartość AUTOINCREMENT
    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Wykladowcy'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Wykladowcy' została opróżniona i zresetowana.<br>";

    // 2. Pobieramy dane z URL
    $data = file_get_contents($url);
    $teachers = json_decode($data, true);

    // 3. Przechodzimy przez każdego wykładowcę
    foreach ($teachers as $teacher) {
        // Rozdzielamy imię i nazwisko
        $fullName = $teacher['item'];
        $nameParts = explode(' ', $fullName);

        if (count($nameParts) > 1) {
            $firstName = array_pop($nameParts); // Ostatnia część to imię
            $lastName = implode(' ', $nameParts); // Pozostałe części to nazwisko
        } else {
            $firstName = $nameParts[0];
            $lastName = '';
        }

        // Przygotowujemy zapytanie do bazy danych
        $sql = "INSERT INTO Wykladowcy (Imie, Nazwisko) VALUES (:firstName, :lastName)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);

        // Wykonujemy zapytanie
        $stmt->execute();
    }

    echo "Dane zostały zaimportowane.<br>";

} catch (PDOException $e) {
    // Obsługa błędów
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
