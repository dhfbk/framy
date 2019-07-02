<?php

exit();

$Times = 10;
$File = "txt/Messaggi_WA_Brambi_clean.txt";

echo "<pre>\n";
require_once("include.inc.php");

$F = file($File);
$thisSent = array();

$query = "SELECT MAX(sentence) m FROM sentence_tokens";
$r = $DB->queryfetch($query);

$sentno = $r["m"] + 1;

foreach ($F as $row) {
    $parts = preg_split("/\s+/", $row);
    for ($j = 0; $j < $Times; $j++) {
        $i = 0;
        foreach ($parts as $part) {
            $i++;
            $data = array();
            $data['filename'] = basename($File);
            $data['sentence'] = $sentno;
            $data['token'] = $part;
            $data['position'] = $i;
            $DB->queryinsert("sentence_tokens", $data);
        }
        $sentno++;
    }
    //     foreach ($thisSent as $i => $token) {
    //         $data = array();
    //         $data['filename'] = basename($filename);
    //         $data['sentence'] = $sentno;
    //         $data['token'] = $token['token'];
    //         $data['position'] = $i + 1;
    //         $data['pos'] = $token['pos'];
    //         $data['lemma'] = $token['lemma'];
    //         $DB->queryinsert("sentence_tokens", $data);
    //     }
    //     // print_r($thisSent);
        
    //     $sentno++;
    //     $thisSent = array();
    // }
}
