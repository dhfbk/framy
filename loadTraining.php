<?php

exit();

require_once("include.inc.php");
$filename = "txt/all-commands-parsed.txt";

$F = file($filename);
$thisSent = array();
$sentno = 1;

foreach ($F as $row) {
//     echo $row;
    $parts = preg_split("/\t+/", $row);
    $arr = array();
    $arr['token'] = $parts[0];
    $arr['pos'] = $parts[1];
    $arr['lemma'] = $parts[2];
    $thisSent[] = $arr;
    if ($parts[1] == "SENT") {
        // Save sentence
        foreach ($thisSent as $i => $token) {
            $data = array();
            $data['filename'] = basename($filename);
            $data['sentence'] = $sentno;
            $data['token'] = $token['token'];
            $data['position'] = $i + 1;
            $data['pos'] = $token['pos'];
            $data['lemma'] = $token['lemma'];
            $DB->queryinsert("sentence_tokens", $data);
        }
        // print_r($thisSent);
        
        $sentno++;
        $thisSent = array();
    }
}

// print_r($F);

