<?php

use App\Model\Teacher;
use App\Service\Config;

require_once "H:/aplikacje_plan/projekcik/src/Model/Teacher.php";
require_once 'H:/aplikacje_plan/projekcik/src/Service/Router.php';
require_once 'H:/aplikacje_plan/projekcik/src/Service/Config.php';

$teacher = new Teacher();

$url = 'https://plan.zut.edu.pl/schedule.php?kind=teacher&query=';

try {

    $teacher->deleteAll();
    $teacher->resetAutoincrement();
    $data = file_get_contents($url);
    $teachers = json_decode($data, true);
    foreach ($teachers as $teacherData) {

        $fullName = $teacherData['item'];
        $nameParts = explode(' ', $fullName);

        if (count($nameParts) > 1) {
            $firstName = array_pop($nameParts);
            $lastName = implode(' ', $nameParts);
        } else {
            $firstName = $nameParts[0];
            $lastName = '';
        }

        $teacher = new Teacher();
        $teacher->setFirstName($firstName)
            ->setLastName($lastName);
        $teacher->save();
    }

    echo "Dane zostały zaimportowane.<br>";

} catch (PDOException $e) {
    echo "Błąd połączenia: " . $e->getMessage();
}
