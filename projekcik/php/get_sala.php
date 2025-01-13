<?php

$url = 'https://plan.zut.edu.pl/schedule.php?kind=room&query=';

$response = file_get_contents($url);
$data = json_decode($response, true);

$prefixes = [
    "BMW ", "CN", "WA", "WBiHZ", "WBiIŚ", "WE WE-A", "WE WE-C",
    "Wekon J ", "Wekon Ż ", "WI WI1- ", "WI WI2- ", "WIMiM HT ",
    "WIMiM KEP ", "WIMiM KTC ", "WIMiM KTE ", "WIMiM WM   ",
    "WKSiR A ", "WKSiR PP1 ", "WKSiR PP3 ", "WKSiR Sł.17 ",
    "WNoZiR A ", "WNoZiR B ", "WNoZiR D ", "WNoZiR J ", "WNoZiR KK ",
    "WNoZiR PP ", "WTMiT ", "WTiICH NCH", "WTiICH SCH"
];

$results = [];
foreach ($prefixes as $prefix) {
    $results[$prefix] = [];
}

foreach ($data as $entry) {
    $item = $entry['item'];
    foreach ($prefixes as $prefix) {
        if (strpos($item, $prefix) === 0) {

            $remaining = substr($item, strlen($prefix));

            $remaining = str_replace(' ', '', $remaining);
            $results[$prefix][] = $remaining;
            break;
        }
    }
}

// Wyświetl wyniki
foreach ($results as $prefix => $items) {
    if (!empty($items)) {
        foreach ($items as $item) {
            echo $item . "\n";
        }
    }
}
?>
