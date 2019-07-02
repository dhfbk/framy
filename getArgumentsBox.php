<?php

require_once("include.inc.php");

$frameID = $_REQUEST['f'];
$sentenceID = $_REQUEST['s'];

$fe = array();

$query = "SELECT * FROM frames WHERE id = '".addslashes($frameID)."'";
if (!$DB->querynum($query)) {
    exit("frame not found");
}
$frameR = $DB->fetch();
echo "<h3>{$frameR['frame']}</h3>";

$query = "SELECT * FROM fe WHERE frame = '".addslashes($frameID)."'";
if (!$DB->querynum($query)) {
    exit("no FE found");
}
echo "<p style='line-height: 2em;'>";
while ($r = $DB->fetch()) {
    echo "<span class='tb_tooltip' id='tb_tooltip_{$r['id']}' title='".htmlentities($r['description'], ENT_QUOTES)."' style='padding: 3px 5px; background-color: #{$r['bgColor']}; color: #{$r['fgColor']};'>{$r['name']} [".substr($r['coreType'], 0, 1)."]</span> ";
    $fe[$r['id']] = $r['name'] . " [{$r['coreType']}]";
}
echo "</p>";
echo "<hr style='clear: both; visibility: hidden;' />\n";

$input_fe = "";
$input_fe .= '<select class=\'tb_token_select\' name=\'%1$s\' id=\'%1$s\' onchange=\'argClicked(this, %2$d);\' size=\'1\'>';
$input_fe .= "<option value='0'></option>\n";
foreach ($fe as $i => $v) {
    $input_fe .= "<option value='$i'>$v</option>\n";
}
$input_fe .= "</select>";

$query = "SELECT * FROM sentence_tokens WHERE sentence = '$sentenceID' ORDER BY position";
if (!$DB->querynum($query)) {
    exit("sentence not found");
}
while ($r = $DB->fetch()) {
    // $r['token'] = utf8_encode($r['token']);
    /*
    if ($r['id'] == $id) {
        $spant = "<span class='lu_keyword'>".$r['token']."</span>";
    }
    else {
        $spant = "<span>".$r['token']."</span>";
    }
    */
    $spant = "<span>".$r['token']."</span>";
    $div = "";
    $div .= "<div class='div_token' id='div_token_{$r['id']}'>";
    $div .= $spant;
    $div .= "<input class='tb_token_checkbox' id='tb_check_{$r['id']}' type='checkbox' onchange='reloadColors();' /> ";
    $div .= sprintf($input_fe, "tb_token_{$r['id']}", $r['id']);
    $div .= "<a href='#' onclick='return arrowClicked({$r['id']});'><img src='m_here.gif' /></a>";
    $div .= "</div>";
    echo $div;
    // echo "{$r['token']} ";
}
echo "<hr style='clear: both;' />\n";

// echo "<input type='checkbox' name='tb_notsure' id='tb_notsure' /><label for='tb_notsure'>I'm not sure</label><br />";
echo "<input type='button' onclick='saveTB($sentenceID, $frameID);' value='Save' />";

?>
<script>

var synset = '<?php echo $synset; ?>';
var id = '<?php echo $id; ?>';

jQuery(".token_" + synset + "_" + id).each(function() {
    var r = /_([0-9]+)$/ig;
    var match = r.exec(jQuery(this).attr("id"));
    var idinput = "#tb_token_" + match[1];
    jQuery(idinput).val(jQuery(this).val());
});

jQuery(".tb_tooltip").tooltip({
    delay: 0,
    opacity: 1
});

reloadColors();

</script>