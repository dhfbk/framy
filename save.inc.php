<?php

// Remove to end debug mode!!!
/*
$query = "UPDATE frames SET done = '0'";
$DB->query($query);
$query = "TRUNCATE TABLE results_frame";
$DB->query($query);
$query = "TRUNCATE TABLE results_synset";
$DB->query($query);
$query = "TRUNCATE TABLE results_sentence";
$DB->query($query);
$query = "TRUNCATE TABLE results_argument";
$DB->query($query);
*/

// Save here!

if (isset($_POST) && count($_POST)) {
    
    $frameID = $_POST['frame'];
    $dati = array();
    $dati['frame'] = $frameID;
    $DB->queryinsert("results_frame", $dati);
    $ID_f = $DB->last_id;
    
    $query = "UPDATE frames SET done = '1' WHERE id = '$frameID'";
    $DB->query($query);
    
    $synsets = array();
    echo "<pre>";
    foreach ($_POST as $index => $value) {
        if (preg_match("/^check_([a-z][0-9]+)$/i", $index, $ris)) {
            $synsets[$ris[1]] = array();
        }
        if (preg_match("/^sentence_([a-z][0-9]+)_([0-9]+)$/i", $index, $ris)) {
            if (isset($synsets[$ris[1]])) {
                $synsets[$ris[1]][$ris[2]] = array();
            }
        }
        if (preg_match("/^token_([a-z][0-9]+)_([0-9]+)_([0-9]+)$/i", $index, $ris)) {
            if (isset($synsets[$ris[1]])) {
                if (isset($synsets[$ris[1]][$ris[2]])) {
                    if ($value) {
                        $synsets[$ris[1]][$ris[2]][$ris[3]] = $value;
                    }
                }
            }
        }
    }
    
    foreach ($synsets as $syns => $sentences) {
        $dati = array();
        $dati['results_frame_id'] = $ID_f;
        $dati['synset'] = $syns;
        $DB->queryinsert("results_synset", $dati);
        $ID_sy = $DB->last_id;
        
        foreach ($sentences as $idtoken => $sentence) {
            $idtoken = addslashes($idtoken);
            $query = "SELECT * FROM sentence_tokens WHERE id = '$idtoken'";
            if (!$DB->querynum($query, 2)) {
                continue;
            }
            $r = $DB->fetch(2);
            
            $dati = array();
            $dati['results_synset_id'] = $ID_sy;
            $dati['filename'] = $r['filename'];
            $dati['sentence'] = $r['sentence'];
            $dati['id_token'] = $idtoken;
            $dati['notsure'] = $_POST['notsure_'.$syns.'_'.$idtoken];
            $DB->queryinsert("results_sentence", $dati);
            $ID_se = $DB->last_id;
            
            foreach ($sentence as $token => $fe) {
                $dati = array();
                $dati['results_sentence_id'] = $ID_se;
                $dati['id_token'] = $token;
                $dati['argument'] = $fe;
                $DB->queryinsert("results_argument", $dati);
            }
        }
    }
    
    print_r($synsets);
    echo "</pre>";
    header("Location: index.php");
    exit();
}
