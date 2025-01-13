<?php
// Dane połączenia do bazy danych
$dsn = 'sqlite:H:/aplikacje_plan/projekcik/php/sql/data.db';
$username = '';
$password = '';

// Rekordy, które chcesz wstawić do tabeli `Sala`
$rooms = [
    "004", "007", "010/011", "015", "016", "118", "119", "124", "210", "211", "214", "228/229",
    "312", "313", "314", "325", "326", "403", "405", "410", "411", "413", "422", "Plener",
    "Stolarnia", "g109", "g111", "g113", "g117", "g203", "g204", "g205", "g207", "g208",
    "g209", "g215", "g216", "g217", "g218", "g219", "g220", "g221", "g222", "g223", "g224",
    "g225", "g304", "g305", "g307", "g309", "g310", "g315", "g316", "g317", "g318", "g319",
    "g320", "g321", "g323", "g402", "g412", "i001(GaleriaForma)", "i009(LaboratoriumNowoczesnegoArchitekta)",
    "DJ10/110", "DJ10/5", "DJ10/Ba12", "DJ10/Ba200", "DJ12/4", "DJ12/B19", "DJ14/anat.",
    "DJ20/102", "DJ20/102s", "DJ20/zool.", "DJ26/11", "DJ26/21", "DJ26/22", "DJ26/5",
    "DJ6/10", "DJ6/101", "DJ6/107", "DJ6/4", "DJ6/6", "DJ6/7", "DJ6/lab.hig.", "DJ/12akomp.L",
    "DJ/12akomp.S", "DJ/12apaw.", "J29/-1/13", "J29/1/22", "J29/-1/08", "J29/-1/18",
    "J29/-1/18sekcyjna", "J29/0/16", "J29/0/22", "J29/0/33", "J29/1/15W", "J29/1/18",
    "J29/1/19", "J29/1/28", "J29/1/30", "J29/2/22", "J29/2/23", "J29/3/06", "J29/3/08",
    "J29/3/09", "J29/3/12", "J29/3/13", "J29/3/19komp.", "J29/3/20komp.", "J291/22",
    "J32/mgr", "J32/-1/04/psy", "J32/0/04", "J32/0/20", "J32/0/21", "J32/1/06", "J32/1/07",
    "J32/1/15ab/RW", "J32/2/03", "J32/2/18komp.", "J32J32/1/06", "J33/0/17", "J33/1/09",
    "J33/0/25", "J33/1/11", "J33/1/19", "J33/2/17", "S221", "Teren1", "Teren2", "Teren3",
    "003", "010", "020", "028", "029", "036", "040", "061", "064", "065", "102", "118",
    "121", "123", "133", "137", "138", "154", "156", "165", "166", "170", "182", "215",
    "216", "226", "226A", "226L", "238", "240", "244", "252", "255", "256", "258", "260",
    "262", "266", "268", "272", "274", "289", "291", "292", "301", "302", "305", "306",
    "308", "309", "312", "315", "316", "326", "327", "328", "330", "344", "346", "350",
    "351", "352", "353", "354", "356", "360", "403", "412", "413", "415", "422", "423",
    "427", "428", "430", "431", "439", "440", "441", "442", "NANO", "czytelnia", "064",
    "05", "08", "106", "107", "109", "110", "116", "117", "120", "203", "204", "205",
    "209", "216", "303", "304", "305", "306", "307", "308", "309", "313", "314", "315",
    "316", "318", "321", "406", "407", "408", "409", "411", "415", "417", "419", "422",
    "Centrum Mechatroniki", "Rada Wydziału", "201", "321", "Centrum Mechatroniki2", "011",
    "029", "038", "039", "101", "105", "107", "111", "116", "118", "122", "125", "126",
    "127", "128", "134", "137", "142", "143", "201", "202", "204", "205", "209", "211",
    "214", "215", "222", "223", "224", "228", "229", "233", "234", "235", "237", "238",
    "239", "240", "301", "302", "307", "308", "309", "311", "313", "319", "323", "325",
    "326", "327", "328", "330", "331", "338", "340", "341", "401", "409", "410", "412",
    "420", "425", "426", "427", "Metrologia", "Zajęcia poza budynkiem","127", "S1ARElementyiukładyelektroniczne", "S1ARProjektowanieukładówsterowania", "S1ARWprowadzeniedoautomatyki", "S1Aplikacjeinternetoweimobilne", "S1Sieciteleinformatyczneitransmisjadanych",
    "001", "003", "004", "006", "007", "010", "011", "013", "100", "112", "113", "119",
    "128", "129", "200", "206", "208", "215", "217", "300", "302", "303", "304", "307",
    "308", "309", "310", "313", "315", "316", "317", "0.03", "0.15", "025", "10", "100",
    "104", "108", "109", "11", "110", "119", "120", "121", "126", "14", "15", "16", "17",
    "200", "201", "212", "221", "222", "227", "24", "300", "304", "308", "312", "317", "318",
    "322", "323", "109", "110", "111", "112", "114", "12", "2", "25", "33", "35", "36", "38",
    "4", "46", "47", "48", "5", "6a", "7", "7b", "P06", "P3", "ROBOT", "W2", "W3", "W3/5",
    "W4", "W5", "W6", "W7", "24/2", "6", "104", "20", "202", "203", "204", "205", "23",
    "24/1", "24/2", "24/3", "26", "29", "3", "5", "7", "8", "9", "Elektrownia", "19",
    "56", "?X", "?XXX", "g34", "g1", "g11", "g12", "g14", "g2", "g3", "g4", "g5", "g6",
    "g7", "g8", "151", "221", "223", "255", "8", "Lab.ChemiiOg.", "S.Mikrobiologi", "07",
    "08", "09c", "104A", "110", "111", "112", "117A", "117B", "117C", "118", "119", "121",
    "201", "208", "211", "301", "304", "311", "Halamaszyn", "XIV", "_Zaj_zdalne", "_Zaj_zdalne1",
    "_Zaj_zdalne2", "_Zaj_zdalne3", "07", "104", "109", "110", "112", "201", "206", "308", "310",
    "314", "414", "7", "8", "9", "SalaSeminaryjnawKatedrze", "_Zaj_zdalne", "_Zaj_zdalne1"
    , "311", "013", "014", "0162", "0166", "0189", "08", "112", "127", "13", "137", "14",
    "15", "151", "152", "153", "19", "20", "201", "204b", "221", "223", "238",
    "255", "301", "304", "315", "58", "68", "87", "Auditorium Maximum",
    "Hala Wegetacyjna", "Sala1 katedralna", "Sala2 katedralna",
    "Sala3 katedralna", "Ter1", "Ter2", "15", "9", "8", "D2", "D7", "D8", "D9",
    "D4", "11", "17", "20", "21", "6", "7", "-5", "-9", "3", "7", "8", "1.10", "1.15",
    "1.16", "2.08", "2.09", "2.10", "2.13", "2.15", "2.29", "ZAJĘCIA TERENOWE", "10",
    "105", "111", "116", "117", "118", "119", "120", "121", "122", "124", "128", "132",
    "206", "211", "217", "218", "219", "220", "222", "224", "32", "AULAim.Prof.A.Winnickiego",
    "B-15", "Przyb.14", "Przyb.16", "SALAKONFERENCYJNA", "218", "10", "102A", "110", "111",
    "114", "117", "13", "204", "208", "211", "213", "216", "221", "222", "303", "304", "309",
    "317", "318", "319", "320", "4", "404", "409", "413", "416", "422", "7,8", "8",
    "HalaA Lab. KKMiTO", "HalaA Lab. ZMT", "HalaA Lab. ZPJiS", "HalaA Lab  BCPM", "HalaB Hala B",
    "HalaB Lab. KKMiTO", "HalaB Lab. KKiTCh", "HalaB Lab. ZBJiS", "KTZO24KTZO", "KTZO25KTZO",
    "LabKlLabKl", "LabWibLabWibr", "112", "114", "115", "116", "117", "119", "120", "121", "16",
    "18", "207", "217", "229", "301", "319", "323", "407", "408", "409", "421", "435", "501",
    "503", "509", "511", "513", "Audyt.", "113", "311", "301", "027", "029", "102", "11",
    "11(ONLINE)", "110", "110(online)", "112", "113", "113a", "116", "117", "118", "118(ONLINE)",
    "119", "121", "127", "128", "129", "13", "133", "134", "135A", "136", "138", "14",
    "144", "148", "16", "19", "205", "209", "21", "211", "212", "215", "221", "222", "226",
    "227", "229", "23", "235", "238", "239", "24", "241", "242", "25", "254", "255", "259",
    "27", "28", "29", "310", "314", "317", "320", "321", "324", "33", "338", "340", "343",
    "347", "35", "355", "36", "37", "401", "402A", "41", "416", "417", "419", "42", "422",
    "429", "43", "431", "432", "433", "437", "439", "44", "440", "445", "446", "447", "449",
    "452", "46", "48", "54", "AudI", "AudII", "DYPLOM", "PUMGIN", "PUMKOP", "PUMMCD1", "SRW(7)",
    "ZajeciaONLINE", "ZajeciaONLINE2", "sala", "46", "013", "016", "017", "019", "025", "028",
    "030", "031", "034", "035", "04", "044", "045", "05", "06", "08", "10", "107", "109(4)",
    "110", "111", "112", "113", "119", "120", "121", "122", "123", "124", "125", "125B", "125C",
    "125A", "126", "128", "13", "130", "131", "132", "133", "136", "137", "138", "139", "140",
    "141", "152", "153", "18", "201", "202", "203", "207", "210", "213", "214", "215", "217",
    "218", "221", "223", "224", "225", "227A", "238", "239", "240", "242", "251", "255", "256",
    "27(2)", "28", "29", "31", "310", "311", "312", "316", "318", "319", "326", "33", "331",
    "333", "334", "335", "35", "37", "38", "Aula", "DYPLOM", "LabPol", "Zajeciazdalne",
    "Zajeciazdalne2", "10", "110", "132", "138", "141", "213", "234", "312", "Hala", "Stajnia", "Ujeżdzalnia"


];

try {
    // Tworzymy obiekt PDO
    $pdo = new PDO($dsn, $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Połączono z bazą danych!<br>";

    // Opróżniamy tabelę Sala
    $sqlDelete = "DELETE FROM Sala";
    $pdo->exec($sqlDelete);

    $sqlResetAutoincrement = "DELETE FROM sqlite_sequence WHERE name='Sala'";
    $pdo->exec($sqlResetAutoincrement);

    echo "Tabela 'Sala' została opróżniona i zresetowana.<br>";

    // Wstawianie sal do tabeli
    foreach ($rooms as $roomName) {
        $sqlCheck = "SELECT COUNT(*) FROM Sala WHERE Nazwa_Sali = :roomName";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->bindParam(':roomName', $roomName);
        $stmtCheck->execute();

        if ($stmtCheck->fetchColumn() == 0) {
            $sqlInsert = "INSERT INTO Sala (Nazwa_Sali) VALUES (:roomName)";
            $stmtInsert = $pdo->prepare($sqlInsert);
            $stmtInsert->bindParam(':roomName', $roomName);
            $stmtInsert->execute();
            echo "Wstawiono salę: $roomName<br>";
        } else {
            echo "Sala $roomName już istnieje.<br>";
        }
    }

    echo "Dane sal zostały zaimportowane.<br>";

} catch (PDOException $e) {
    echo "Błąd: " . $e->getMessage();
}
?>
