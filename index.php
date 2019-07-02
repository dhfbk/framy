<?php

// RESET
// UPDATE sentence_tokens SET frame_done = 0, frame_id = null, frame_element = null;

require_once("include.inc.php");
// require_once("save.inc.php");
require_once("header.inc.php");

$HowMany = 2192;
$Offset = 0;
$Page = $_REQUEST['page'];

if (!$Page || !is_numeric($Page)) {
	$Page = 1;
}

$where = "";
// if (isset($_REQUEST['part']) && is_numeric($_REQUEST['part'])) {
//     $p = addslashes($_REQUEST['part']);
//     $where = " WHERE sentence % 10 = $p ";
// }
// else {
//     exit();
// }

$query = "SELECT DISTINCT(sentence) FROM sentence_tokens $where ORDER BY sentence";
$n = $DB->querynum($query);
$perPage = $n / $HowMany;

$perPage = 10;

$IDs = array();
while ($r = $DB->fetch()) {
	$IDs[] = $r['sentence'];
}

$start = $perPage * ($Page - 1);
$end = $start + $perPage;

// Sentences
for ($i = $start; $i < $end; $i++) { 
	if (!isset($IDs[$i])) {
		continue;
	}
	$sID = $IDs[$i];

    echo "<p id='sent_{$sID}'>";
    printP($sID);
    echo "</p>";
}

echo "<p>";
$stuff = array();
if ($Page > 1) {
	$stuff[] = "<a href='?page=" . ($Page - 1). "'>Previous</a>";
}
$stuff[] = "<a href='?page=" . ($Page + 1). "'>Next</a>";
echo implode(" | ", $stuff);
echo "</p>";
// while ($r = $DB->fetch(2)) {
//     echo "<p id='sent_{$r['sentence']}'>";
//     printP($r['sentence']);
//     echo "</p>";
// }

// $query = "SELECT * FROM frames WHERE done = '0' ORDER BY RAND() LIMIT 1";
// if (isset($_REQUEST['useFrame'])) {
//     $useFrame = addslashes($_REQUEST['useFrame']);
//     $query = "SELECT * FROM frames WHERE frame = '$useFrame' ORDER BY RAND() LIMIT 1";
// }
// $rF = $DB->queryfetch($query);
// $frameID = $rF['id'];
// 
// echo "<h2><a target='_blank' href='https://framenet2.icsi.berkeley.edu/fnReports/data/frame/{$rF['frame']}.xml'>{$rF['frame']}</a></h2>";
// echo "<p>{$rF['description']}</p>";
// 
// $query = "SELECT * FROM lu WHERE frame = '$frameID'";
// $DB->query($query);
// echo "<form method='post'>";
// echo "<input type='hidden' name='frame' value='$frameID' />";
// echo "<table id='list_of_words' cellspacing='0' cellpadding='5'>";
// $i = 0;
// $done = array();
// 
// while ($r = $DB->fetch()) {
//     preg_match("/^(.*)\.(.+?)$/i", $r['lemma'], $ris);
//     $pos = $ris[2];
//     $lemma = $ris[1];
//     
//     // Tolgo le preposizioni
//     if ($pos == "n" || $pos == "v" || $pos == "a") {
//     }
//     elseif ($pos == "adv") {
//         $pos = "r";
//     }
//     else {
//         continue;
//     }
//     
//     $row1 = "";
//     // $row1 .= "{$r['lemma']}<br />\n";
//     
//     $lemma = preg_replace("/\(.*\)/i", "", $lemma);
//     $lemma = str_replace("_", " ", $lemma);
//     $lemma = trim($lemma);
//     $row1 .= "$lemma $pos";
//     
//     $lemma = addslashes($lemma);
//     $query = "SELECT * FROM $Tmw.english_index WHERE lemma = '$lemma' AND search_$pos IS NOT NULL AND search_$pos != ''";
//     if ($DB->querynum($query, 2)) {
//         while ($rL = $DB->fetch(2)) {
//             $parts = explode(" ", $rL["id_$pos"]);
//             foreach ($parts as $part) {
//                 if (trim($part)) {
//                     $row2 = $part;
//                     
//                     $partOK = preg_replace("/[^0-9a-z]/", "", $part);
//                     if (isset($done[$partOK])) {
//                         continue;
//                     }
//                     $done[$partOK] = 1;
//                     
//                     $txt1 = "";
//                     $query = "SELECT * FROM $Tmw.english_synset WHERE id = '$part'";
//                     if ($DB->querynum($query, 3)) {
//                         $rL2 = $DB->fetch(3);
//                         $pg = explode(";", $rL2['gloss']);
//                         $pg = array_map("trim", $pg);
//                         $pg = array_map("stripslashes", $pg);
//                         foreach ($pg as $pgt) {
//                             $txt1 .= $pgt ? $pgt."<br />\n" : "";
//                         }
//                         // $txt1 = $rL2['gloss'];
//                         // $txt1 .= print_r($rL2, true);
//                     }
//                     $txt2 = "";
//                     $query = "SELECT * FROM $Tmw.italian_synset WHERE id = '$part'";
//                     if ($DB->querynum($query, 3)) {
//                         $rL2 = $DB->fetch(3);
//                         $txt2 = "";
//                         $txt2 .= $rL2['word']."<br />\n";
//                         $pg = explode(";", $rL2['gloss']);
//                         $pg = array_map("trim", $pg);
//                         // $pg = array_map("stripslashes", $pg);
//                         foreach ($pg as $pgt) {
//                             $txt2 .= $pgt ? $pgt."<br />\n" : "";
//                         }
//                         // $txt2 .= $rL2['word']."<br />\n".$rL2['gloss'];
//                         
//                         $query = "SELECT DISTINCT id, filename, sentence, position FROM sentence_tokens WHERE wnsn = '$part'";
//                         if ($DB->querynum($query, 4)) {
//                             while ($rT = $DB->fetch(4)) {
//                                 $txt2 .= "<p class='example_sentence'>";
//                                 $txt2 .= "<input type='checkbox' id='sentence_{$partOK}_{$rT['id']}' name='sentence_{$partOK}_{$rT['id']}' onclick='return getArgumentsBox(this, $frameID, \"{$rT['filename']}\", \"{$partOK}\", {$rT['id']});' /> ";
//                                 $txt2 .= "<input type='hidden' name='notsure_{$partOK}_{$rT['id']}' id='notsure_{$partOK}_{$rT['id']}' value='' />";
//                                 $query = "SELECT * FROM sentence_tokens WHERE filename = '{$rT['filename']}' AND sentence = '{$rT['sentence']}'";
//                                 $rS = $DB->query($query, 5);
//                                 while ($rS = $DB->fetch(5)) {
//                                     if ($rS['position'] == $rT['position']) {
//                                         $txt2 .= "<span style='color: #800;'>".utf8_encode($rS['token'])."</span> ";
//                                     }
//                                     else {
//                                         $txt2 .= utf8_encode($rS['token'])." ";
//                                     }
//                                     $txt2 .= "<input class='token_{$partOK}_{$rT['id']}' type='hidden' name='token_{$partOK}_{$rT['id']}_{$rS['id']}' id='token_{$partOK}_{$rT['id']}_{$rS['id']}' value='' />";
//                                 }
//                                 $txt2 .= "</p>";
//                             }
//                         }
//                         // $txt2 .= print_r($rL2, true);
//                     }
//                     if ($i++ % 2) {
//                         echo "<tr style='background-color: #ddd;'>";
//                     }
//                     else {
//                         echo "<tr style='background-color: #eee;'>";
//                     }
//                     echo "<td width='10%'>$row1</td>";
//                     echo "<td width='10%'>$row2</td>";
//                     // echo "<td width='10%'><input type='checkbox' id='check_$partOK' name='check_$partOK' /> <label for='check_$partOK'>$row2</label></td>";
//                     echo "<td width='30%'>$txt1</td>";
//                     echo "<td width='50%'>".($txt2 ? "<input type='checkbox' id='check_$partOK' name='check_$partOK' /> <label for='check_$partOK'>$row2</label><br />$txt2" : "")."</td>";
//                     echo "</tr>";
//                 }
//             }
//             // echo $rL["id_$pos"]."\n";
//         }
//     }
// }
// echo "</table>";
// echo "<input type='submit' />";
// echo "</form>";

require_once("footer.inc.php");
