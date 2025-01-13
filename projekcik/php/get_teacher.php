<?php
$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';
$username = '';
$password = '';

$url = 'https://plan.zut.edu.pl/schedule.php?kind=teacher&query=';


try {

    $pdo = new PDO($dsn, $username, $password);

    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Połączono z bazą danych!<br>";

    $sqlDelete = "DELETE FROM Wykladowcy";
    $pdo->exec($sqlDelete);

    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Wykladowcy'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Wykladowcy' została opróżniona i zresetowana.<br>";

    $data = file_get_contents($url);
    $teachers = json_decode($data, true);


    foreach ($teachers as $teacher) {

        $fullName = $teacher['item'];
        $nameParts = explode(' ', $fullName);

        if (count($nameParts) > 1) {
            $firstName = array_pop($nameParts);
            $lastName = implode(' ', $nameParts);
        } else {
            $firstName = $nameParts[0];
            $lastName = '';
        }


        $sql = "INSERT INTO Wykladowcy (Imie, Nazwisko) VALUES (:firstName, :lastName)";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':firstName', $firstName);
        $stmt->bindParam(':lastName', $lastName);


        $stmt->execute();
    }

    echo "Dane zostały zaimportowane.<br>";

} catch (PDOException $e) {

    echo "Błąd połączenia: " . $e->getMessage();
}
?>
