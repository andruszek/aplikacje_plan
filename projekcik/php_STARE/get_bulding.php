<?php

$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';
$username = '';
$password = '';


$buildings = [
    "CN", "STKM", "BMW", "WM", "HS", "BibG", "RCliTT", "TechnoPark",
    "WI1", "WI2", "WA", "WBiHZ", "WBiIŚ", "WE-A", "WE-C", "WE-CS1",
    "WE-C127", "WEkon J", "WEkon Ż", "WIMiM CM", "WIMiM HT", "WIMiM KEP",
    "WIMiM KTC", "WIMiM KTE", "WIMiM WM", "WKSiR A", "WKSiR PP1", "WKSiR PP3",
    "WKSiR Sł.17", "WNoZiR A", "WNoZiR B", "WNoZiR C", "WNoZiR D", "WNoZiR J",
    "WNoZiR KK", "WNoZiR PP", "MWTiT", "WTMiT KTZO", "WTMiT Lab Kl",
    "WTiICH SCH", "WTiICH NCH", "WTMiT WTM"
];


try {

    $pdo = new PDO($dsn, $username, $password);


    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Połączono z bazą danych!<br>";


    $sqlDelete = "DELETE FROM Budynek";
    $pdo->exec($sqlDelete);
    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Budynek'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Budynek' została opróżniona i zresetowana.<br>";

    foreach ($buildings as $buildingName) {
        $sqlCheck = "SELECT COUNT(*) FROM Budynek WHERE Nazwa_Budynku = :buildingName";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->bindParam(':buildingName', $buildingName);
        $stmtCheck->execute();
        $count = $stmtCheck->fetchColumn();

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
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
