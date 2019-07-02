<?php

echo "<pre>\n";
require_once("include.inc.php");

$sentences = array();
$query = "SELECT DISTINCT sentence FROM sentence_tokens WHERE frame_id IS NOT NULL";
if ($DB->querynum($query)) {
    while ($r = $DB->fetch()) {
        $sentences[] = $r['sentence'];
    }
}

// $Export = array();
// $Tokens = array();
$Annotations = array();

foreach ($sentences as $sentence) {
    $diff = $sentence % 5;
    $diff = $diff == 0 ? 5 : $diff;
    $diff = ($sentence - $diff) / 5 + 1;
    // $export[] = $sentence - $diff;

    $query = "SELECT t.*, f.frame frame_name, e.name fe_name FROM sentence_tokens t
        LEFT JOIN frames f ON f.id = t.frame_id
        LEFT JOIN fe e ON e.id = t.frame_element
        WHERE sentence = '$sentence'
        ORDER BY position";
    $DB->query($query);
    $tokens = array();
    $framename = "";
    $framespan = array();
    $fe = array();
    while ($r = $DB->fetch()) {
        $tokens[] = $r['token'];
        if ($r['fe_name']) {
            if (!isset($fe[$r['fe_name']])) {
                $fe[$r['fe_name']] = array();
            }
            $fe[$r['fe_name']][] = $r['position'];
        }
        if ($r['frame_name']) {
            $framename = $r['frame_name'];
            $framespan[] = $r['position'];
        }
    }
    // if (!isset($Tokens[$diff])) {
    //     $Tokens[$diff] = $tokens;
    // }
    if (!$framename) {
        continue;
    }
    
    if (!isset($Annotations[$diff])) {
        $Annotations[$diff] = array();
        $Annotations[$diff]["annotations"] = array();
    }
    $Annotations[$diff]["annotations"][] = array("frame" => $framename, "span" => $framespan, "fes" => $fe);
    $Annotations[$diff]["tokens"] = $tokens;
    
    // $tokens = array();
    // while ($r = $DB->fetch()) {
    //     $thisR = array();
    //     $thisR['position'] = $r['position'];
    //     $thisR['token'] = $r['token'];
    //     $thisR['fe_name'] = $r['fe_name'];
    //     $thisR['frame_name'] = $r['frame_name'];
    //     $tokens[] = $thisR;
    // }
    // $export[] = $tokens;
}

// $Export['tokens'] = $Tokens;
// $Export['annotations'] = $Annotations;
echo json_encode($Annotations, JSON_PRETTY_PRINT);
