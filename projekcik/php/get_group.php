<?php

$dsn = 'sqlite:I:/sem5/aplikacje_plan/projekcik/php/sql/data.db';

try {

    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Połączono z bazą danych!<br>";

    $sqlDelete = "DELETE FROM Grupa";
    $pdo->exec($sqlDelete);
    $pdo->exec("DELETE FROM sqlite_sequence WHERE name='Grupa'");
    echo "Tabela 'Grupa' została opróżniona i licznik AUTOINCREMENT zresetowany.<br>";


    $url = 'https://plan.zut.edu.pl/schedule.php?kind=group';
    $response = file_get_contents($url);

    if ($response === false) {
        throw new Exception("Nie udało się pobrać danych z API: $url");
    }


    $cleanedResponse = preg_replace('/^Warning.*$/m', '', $response);


    $groups = json_decode($cleanedResponse, true);



    $sqlInsert = "INSERT INTO Grupa (Nazwa_Grupy) SELECT :nazwaGrupy WHERE NOT EXISTS (SELECT 1 FROM Grupa WHERE Nazwa_Grupy = :nazwaGrupy)";
    $stmt = $pdo->prepare($sqlInsert);

    foreach ($groups as $group) {
        if (isset($group['item'])) {
            $groupName = trim($group['item']); //


            $stmt->bindParam(':nazwaGrupy', $groupName);
            $stmt->execute();

            echo "Przetworzono grupę: $groupName<br>";
        }
    }

    echo "Operacja zakończona pomyślnie!";
} catch (PDOException $e) {
    echo "Błąd połączenia z bazą danych: " . $e->getMessage();
} catch (Exception $e) {
    echo "Błąd: " . $e->getMessage();
}
?>
