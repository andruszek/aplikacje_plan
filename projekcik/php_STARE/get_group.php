<?php

require_once "H:/aplikacje_plan/projekcik/src/Model/Groups.php";
require_once "H:/aplikacje_plan/projekcik/src/Service/Config.php";
use App\Model\Groups;
use App\Service\Config;

try {
    $groupModel = new Groups();

    // Usuwamy wszystkie rekordy w tabeli
    $groupModel->deleteAll();
    $groupModel->resetAutoincrement();

    // Połączenie z bazą danych
    $dsn = 'sqlite:I:/sem5/aplikacje_plan/projekcik/php/sql/data.db';
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Pobieranie danych z API
    $url = 'https://plan.zut.edu.pl/schedule.php?kind=group';
    $response = file_get_contents($url);

    if ($response === false) {
        throw new Exception("Nie udało się pobrać danych z API: $url");
    }

    // Czyszczenie odpowiedzi
    $cleanedResponse = preg_replace('/^Warning.*$/m', '', $response);
    $groups = json_decode($cleanedResponse, true);

    foreach ($groups as $group) {
        if (isset($group['item'])) {
            $groupName = trim($group['item']);

            // Sprawdzamy, czy grupa już istnieje
            $existingGroup = Groups::findByName($groupName);

            if (!$existingGroup) {
                // Tworzymy nową grupę i zapisujemy ją
                $newGroup = new Groups();
                $newGroup->setGroup_Name($groupName);
                $newGroup->save();

                echo "Przetworzono grupę: $groupName<br>";
            } else {
                echo "Grupa $groupName już istnieje w tabeli.<br>";
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
