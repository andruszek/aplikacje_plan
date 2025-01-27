<?php
use App\Model\Department;
use App\Service\Config;


require_once  "H:/aplikacje_plan/projekcik/src/Model/Department.php";
require_once 'H:/aplikacje_plan/projekcik/src/Service/Router.php';
require_once 'H:/aplikacje_plan/projekcik/src/Service/Config.php';


$url = 'https://plan.zut.edu.pl/schedule.php?kind=room&query=';
$department = new Department();

try {
    $department->deleteAll();
    $department->resetAutoincrement();
    $data = file_get_contents($url);
    $rooms = json_decode($data, true);


    foreach ($rooms as $room) {
        try {
            $item = $room['item'];
            $departmentName = strtok($item, ' _');
            if (Department::findByName($departmentName) === null) {
                $department->setDepartment_Name($departmentName);
                $department->save();
                echo "Wstawiono wydział: $departmentName<br>";
            } else {
                echo "Wydział $departmentName już istnieje.<br>";
            }
        } catch (PDOException $e) {
            echo "Błąd podczas wstawiania danych: " . $e->getMessage() . "<br>";
        }
    }

    echo "Dane zostały zaimportowane.<br>";

} catch (PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}
?>
