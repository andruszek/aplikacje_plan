<?php

use App\Service\Config;
require_once "H:/aplikacje_plan/projekcik/src/Service/Config.php";
header('Content-Type: application/json; charset=utf-8');
$pdo = new \PDO(Config::get('db_dsn'), Config::get('db_user'), Config::get('db_pass'));


$subjectName = isset($_GET['subjectName']) ? $_GET['subjectName'] : '';
$teacherName = isset($_GET['teacherName']) ? $_GET['teacherName'] : '';
$roomName = isset($_GET['roomName']) ? $_GET['roomName'] : '';
$groupName = isset($_GET['groupName']) ? $_GET['groupName'] : '';
$buildingName = isset($_GET['buildingName']) ? $_GET['buildingName'] : '';

// Budowanie zapytania SQL dynamicznie
$sql = "
    SELECT 
        Subject.Subject_Name,
        Classes.Start,
        Classes.End,
        Classes.Status,
        Building.Building_Name,
        Classes.Room_Name,
        Classes.Date,
        Teacher.FirstName,   
        Teacher.LastName     
    FROM Classes
    JOIN Subject ON Classes.Subject_ID = Subject.Subject_ID
    JOIN Building ON Classes.Building_ID = Building.Building_ID
    JOIN Teacher ON Classes.Teacher_ID = Teacher.Teacher_ID
    JOIN Groups ON Classes.Group_ID = Groups.Group_ID
    WHERE 1=1
";

$params = [];
if (!empty($subjectName)) {
    $sql .= " AND Subject.Subject_Name LIKE :subjectName";
    $params[':subjectName'] = "%$subjectName%";
}
if (!empty($teacherName)) {
    $sql .= " AND (Teacher.FirstName || ' ' || Teacher.LastName) LIKE :teacherName";
    $params[':teacherName'] = "%$teacherName%";
}
if (!empty($roomName)) {
    $sql .= " AND Classes.Room_Name LIKE :roomName";
    $params[':roomName'] = "%$roomName%";
}
if (!empty($groupName)) {
    $sql .= " AND Groups.Group_Name LIKE :groupName";
    $params[':groupName'] = "%$groupName%";
}
if (!empty($buildingName)) {
    $sql .= " AND Building.Building_Name LIKE :buildingName";
    $params[':buildingName'] = "%$buildingName%";
}

$stmt = $pdo->prepare($sql);


foreach ($params as $key => $value) {
    $stmt->bindValue($key, $value, PDO::PARAM_STR);
}

$stmt->execute();
$results = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Zwrócenie danych jako JSON
header('Content-Type: application/json; charset=utf-8');
echo json_encode($results, JSON_UNESCAPED_UNICODE);
?>
