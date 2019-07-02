<?php

require_once("include.inc.php");

$frameID = addslashes($_REQUEST['f']);
$sentenceID = addslashes($_REQUEST['s']);
$check = addslashes($_REQUEST['check']);
$sel = addslashes($_REQUEST['sel']);

$sels = explode(",", $sel);
$checks = explode(",", $check);

if (count($checks)) {
    $query = "UPDATE sentence_tokens SET frame_done = '1' WHERE sentence = '$sentenceID'";
    $DB->query($query);
    $query = "UPDATE sentence_tokens SET frame_id = NULL WHERE sentence = '$sentenceID'";
    $DB->query($query);
    $query = "UPDATE sentence_tokens SET frame_element = NULL WHERE sentence = '$sentenceID'";
    $DB->query($query);
    
    foreach ($checks as $c) {
        $data = array();
        $data['frame_id'] = $frameID;
        $DB->queryupdate("sentence_tokens", $data, array("id" => $c));
    }
    
    foreach ($sels as $s) {
        $parts = preg_split("/-/", $s);
        $token = $parts[0];
        $fe = $parts[1];
        $data = array();
        $data['frame_element'] = $fe;
        $DB->queryupdate("sentence_tokens", $data, array("id" => $token));
    }
}


print_r($_REQUEST);