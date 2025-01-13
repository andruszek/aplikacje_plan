<?php
header('Content-Type: application/json');

$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';

try {
    $pdo = new PDO($dsn);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $subject = "Aplikacje Internetowe 1";

    if (empty($subject)) {
        echo json_encode(['error' => 'Brak nazwy przedmiotu']);
        exit;
    }

    $subjectSql = "SELECT ID_Przedmiotu FROM Przedmioty WHERE Nazwa_Przedmiotu = :subject";
    $subjectStmt = $pdo->prepare($subjectSql);
    $subjectStmt->bindParam(':subject', $subject, PDO::PARAM_STR);
    $subjectStmt->execute();
    $subjectRow = $subjectStmt->fetch(PDO::FETCH_ASSOC);

    if (!$subjectRow) {
        echo json_encode(['error' => 'Nie znaleziono przedmiotu']);
        exit;
    }

    $subjectId = $subjectRow['ID_Przedmiotu'];


    $sql = "
        SELECT 
            Z.ID_Zajec, Z.ID_Przedmiotu, Z.Sala, Z.Godzina_Startu, Z.Godzina_Konca,
            Z.Status, B.Nazwa_Budynku, P.Nazwa_Przedmiotu
        FROM 
            Zajecia Z
        JOIN 
            Budynek B ON Z.ID_Budynku = B.ID_Budynku
        JOIN 
            Przedmioty P ON Z.ID_Przedmiotu = P.ID_Przedmiotu
        WHERE 
            Z.ID_Przedmiotu = :subjectId
    ";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':subjectId', $subjectId, PDO::PARAM_INT);
    $stmt->execute();

    $events = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $events[] = [
            'title' => $row['Nazwa_Przedmiotu'],
            'start' => $row['Data'] . 'T' . $row['Godzina_Startu'],
            'end' => $row['Data'] . 'T' . $row['Godzina_Konca'],
            'extendedProps' => [
                'forma_zajec' => $row['Status'],
                'budynek' => $row['Nazwa_Budynku'],
                'sala' => $row['Sala'],
            ],
        ];
    }

    echo json_encode($events);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Błąd bazy danych: ' . $e->getMessage()]);
}
