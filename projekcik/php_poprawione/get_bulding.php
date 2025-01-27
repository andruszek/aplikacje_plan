<?php

require_once 'H:/aplikacje_plan/projekcik/src/Model/Building.php';
require_once 'H:/aplikacje_plan/projekcik/src/Service/Config.php';
use App\Model\Building;
use App\Service\Config;


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


    $buildingModel = new Building();

    $buildingModel->deleteAll();
    $buildingModel->resetAutoincrement();

    echo "Tabela 'Budynek' została opróżniona i zresetowana.<br>";

    foreach ($buildings as $buildingName) {
        $existingBuilding = Building::findByName($buildingName);

        if (!$existingBuilding) {
            $newBuilding = new Building();
            $newBuilding->setBuilding_Name($buildingName);
            $newBuilding->save();
            echo "Wstawiono budynek: $buildingName<br>";
        } else {
            echo "Budynek $buildingName już istnieje w tabeli.<br>";
        }
    }

    echo "Dane zostały zaimportowane.<br>";

} catch (PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}
