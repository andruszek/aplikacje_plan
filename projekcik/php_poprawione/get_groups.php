<?php

require_once "H:/aplikacje_plan/projekcik/src/Model/Groups.php";
require_once "H:/aplikacje_plan/projekcik/src/Service/Config.php";
use App\Model\Groups;
use App\Service\Config;

try {
    $groupModel = new Groups();
    $groupModel->deleteAll();
    $groupModel->resetAutoincrement();

    $url = 'https://plan.zut.edu.pl/schedule.php?kind=group';
    $response = file_get_contents($url);

    if ($response === false) {
        throw new Exception("Nie udało się pobrać danych z API: $url");
    }

    $cleanedResponse = preg_replace('/^Warning.*$/m', '', $response);
    $groups = json_decode($cleanedResponse, true);

    $processedGroups = [];

    foreach ($groups as $group) {
        if (isset($group['item'])) {
            $groupName = trim($group['item']);

            if (!in_array($groupName, $processedGroups)) {
                $processedGroups[] = $groupName;

                $existingGroup = Groups::findByName($groupName);

                if (!$existingGroup) {
                    $newGroup = new Groups();
                    $newGroup->setGroup_Name($groupName);
                    $newGroup->save();

                    echo "Przetworzono grupę: $groupName<br>";
                } else {
                    echo "Grupa $groupName już istnieje w tabeli.<br>";
                }
            } else {
                echo "Grupa $groupName została już przetworzona, pomijam.<br>";
            }
        }
    }

    echo "Operacja zakończona pomyślnie!";
} catch (Exception $e) {
    echo "Błąd: " . $e->getMessage();
}
