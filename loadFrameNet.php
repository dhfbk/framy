<?php

exit();

$addr = "frame";

echo "<pre>\n";

require_once("include.inc.php");

$query = "TRUNCATE TABLE frames;";
$DB->query($query);
$query = "TRUNCATE TABLE fe;";
$DB->query($query);
$query = "TRUNCATE TABLE lu;";
$DB->query($query);

$files = scandir($addr);
foreach ($files as $file) {
	if (substr($file, -3) !== "xml") {
		continue;
	}

	$content = file_get_contents("$addr/$file");
	$xml = simplexml_load_string($content) or die("Error: Cannot create object");
	
	$data = array();
	$data['frame'] = (string) $xml->attributes()['name'];
	$data['description'] = strip_tags((string) $xml->definition);
	$DB->queryinsert("frames", $data);

	$IDframe = $DB->last_id;
	foreach ($xml->FE as $fe) {
		$attributes = $fe->attributes();

		$data = array();
		$data['frame'] = $IDframe;
		$data['coreType'] = (string) $attributes['coreType'];
		$data['abbrev'] = (string) $attributes['abbrev'];
		$data['name'] = (string) $attributes['name'];
		$data['fe_id'] = (string) $attributes['ID'];
		$data['bgColor'] = (string) $attributes['bgColor'];
		$data['fgColor'] = (string) $attributes['fgColor'];
		$data['description'] = (string) $fe->definition;

		$DB->queryinsert("fe", $data);
	}
}

// $xml = simplexml_load_string($myXMLData) or die("Error: Cannot create object");
