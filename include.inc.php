<?php

require_once("Mysql_connector.class.php");
require_once("config.inc.php");

$DB = new Mysql_connector($db_host, $db_user, $db_pass);
$DB->select_db($db_db);
$DB->debug = true;

function printP($sentenceID) {
    global $DB;
    $query = "SELECT t.*, e.bgColor, e.fgColor, f.frame
        FROM sentence_tokens t
            LEFT JOIN frames f ON f.id = t.frame_id
            LEFT JOIN fe e ON e.id = t.frame_element
        WHERE t.sentence = '{$sentenceID}'
        ORDER BY t.position";
    if (!$DB->querynum($query, 57)) {
        return;
    }
    $frame = "";
    echo "$sentenceID - ";
    while ($row = $DB->fetch(57)) {
        echo "<input type='hidden' name='token_{$sentenceID}_{$row['position']}' value='' />";
        $span = "";
        if ($row['frame_element']) {
            $span = "<span style='color: #{$row['fgColor']}; background-color: #{$row['bgColor']};'>{$row['token']}</span>";
        }
        else {
            $span = "{$row['token']}";
        }
        if ($row['frame_id']) {
            $span = "<u>$span</u>";
        }
        echo $span;
        echo " ";
        if ($row['frame']) {
            $frame = $row['frame'];
        }
    }
    if ($frame) {
        echo " ";
        echo "[$frame]";
    }
    echo " ";
    echo "<select id='frame_{$sentenceID}'>";
    echo "<option value=''>[Selezionare]</option>\n";
    $query = "SELECT * FROM frames ORDER BY frame";
    $DB->query($query, 57);
    while ($rf = $DB->fetch(57)) {
        echo "<option value='{$rf['id']}'>{$rf['frame']}</option>\n";
    }
    echo "</select>";
    echo " ";
    echo "<a href='#' onclick=\"return getArgumentsBox({$sentenceID}, jQuery('#frame_{$sentenceID}').val());\">Annota</a>";
}
