<?php

$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';
$username = '';
$password = '';


$url = 'https://plan.zut.edu.pl/schedule.php?kind=room&query=';


try {

    $pdo = new PDO($dsn, $username, $password);


    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Połączono z bazą danych!<br>";


    $sqlDelete = "DELETE FROM Wydzial";
    $pdo->exec($sqlDelete);


    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Wydzial'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Wydzial' została opróżniona i zresetowana.<br>";


    $data = file_get_contents($url);

    $rooms = json_decode($data, true);
    foreach ($rooms as $room) {
        try {
            $item = $room['item'];

            $departmentName = strtok($item, ' _');
            $sqlCheck = "SELECT COUNT(*) FROM Wydzial WHERE Nazwa_Wydzialu = :departmentName";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':departmentName', $departmentName);
            $stmtCheck->execute();
            $count = $stmtCheck->fetchColumn();

            if ($count == 0) {
                $sqlInsert = "INSERT INTO Wydzial (Nazwa_Wydzialu) VALUES (:departmentName)";
                $stmtInsert = $pdo->prepare($sqlInsert);
                $stmtInsert->bindParam(':departmentName', $departmentName);
                $stmtInsert->execute();
                echo "Wstawiono wydział: $departmentName<br>";
            } else {
                echo "Wydział $departmentName już istnieje.<br>";
            }
        } catch (PDOException $e) {
            echo "Błąd podczas wstawiania danych: " . $e->getMessage() . "<br>";
        }
    }
} catch (PDOException $e) {

    echo "Błąd połączenia: " . $e->getMessage();
}
?>
