<?php
$fileName = "exemple_homepage.html";
$ch = curl_init("http://www.catipsum.com/");
$fp = fopen("$fileName", "w");

curl_setopt($ch, CURLOPT_FILE, $fp);
curl_setopt($ch, CURLOPT_HEADER, 0);

curl_exec($ch);
curl_close($ch);
fclose($fp);

$words = [
    'my' => 0,
    'cat' => 0,
    'ipsum' => 0,
];

$content = file_get_contents($fileName);
$content = strip_tags($content);


/*
$content = explode(' ', $content);
foreach ($words as $word => $amount) {
    foreach ($content as $item){
    echo $word . '<br>';}
}
*/

foreach ($words as $word => $amount){
    $words[$word] = substr_count($content, $word);
}
var_dump($words);

/*
     * Ta bort alla html-taggar (finns inbyggd funktion för det)
     * Dela upp texststrängen (finns inbyggd funktion för det)
     * Räkna orden (en loop kanske?)
     * Skriv ut resultatet på skärmen
 */

