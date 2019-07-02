<?php

require_once("include.inc.php");

$Fsent = fopen("/var/tmp/f.sent", "w");
$Ffrel = fopen("/var/tmp/f.frel", "w");
$SentenceNO = 0;

if (isset($_REQUEST['deleteSentence'])) {
    if (preg_match("/^[0-9]+$/", $_REQUEST['deleteSentence'])) {
        $query = "UPDATE results_sentence SET deleted = '1' WHERE id = '{$_REQUEST['deleteSentence']}'";
        $DB->query($query);
    }
    header("Location: printResults.php");
}

if (isset($_REQUEST['deleteFrame'])) {
    if (preg_match("/^[0-9]+$/", $_REQUEST['deleteFrame'])) {
        $query = "UPDATE results_frame SET deleted = '1' WHERE id = '{$_REQUEST['deleteFrame']}'";
        $DB->query($query);
    }
    header("Location: printResults.php");
}

if (isset($_REQUEST['deleteSynset'])) {
    if (preg_match("/^[0-9]+$/", $_REQUEST['deleteSynset'])) {
        $query = "UPDATE results_synset SET deleted = '1' WHERE id = '{$_REQUEST['deleteSynset']}'";
        $DB->query($query);
    }
    header("Location: printResults.php");
}

require_once("header.inc.php");

echo "<h2>Annotated data</h2>";

$query = "SELECT r.id, f.id id_frame, f.frame FROM results_frame r
    LEFT JOIN frames f ON f.id = r.frame
    WHERE r.deleted = '0'
    ORDER BY f.frame";
if ($DB->querynum($query, 1)) {
    while ($rF = $DB->fetch(1)) {
        echo "<hr />\n";
        $FrameName = $rF['frame'];
        echo "<h3>{$rF['frame']}</h3>";
        echo "<p><a href='?deleteFrame={$rF['id']}'>Delete</a></p>";
        
        $fe = array();
        $query = "SELECT * FROM fe WHERE frame = '{$rF['id_frame']}'";
        if ($DB->querynum($query, 2)) {
            echo "<p style='line-height: 2em;'>";
            while ($rFE = $DB->fetch(2)) {
                echo "<span style='padding: 3px 5px; background-color: #{$rFE['bgColor']}; color: #{$rFE['fgColor']};'>{$rFE['name']} [".substr($rFE['coreType'], 0, 1)."]</span> ";
                $fe[$rFE['id']] = $rFE;
            }
            echo "</p>";
            echo "<hr style='clear: both; visibility: hidden;' />\n";
        }
        // print_r($fe);
        
        $query = "SELECT * FROM results_synset WHERE deleted = '0' AND results_frame_id = '{$rF['id']}'";
        if ($DB->querynum($query, 2)) {
            echo "<ul>";
            while ($rSy = $DB->fetch(2)) {
                echo "<li>";
                $synset = substr($rSy['synset'], 0, 1)."#".substr($rSy['synset'], 1);
                $query = "SELECT * FROM $Tmw.italian_synset WHERE id = '$synset'";
                if (!$DB->querynum($query, 3)) {
                    continue;
                }
                $rSy2 = $DB->fetch(3);
                echo "<a href='?deleteSynset={$rSy['id']}'>Delete</a> ";
                echo "<strong>$synset - {$rSy2['word']}</strong><br />\n";
                
                $query = "SELECT * FROM results_sentence WHERE deleted = '0' AND results_synset_id = '{$rSy['id']}'";
                if ($DB->querynum($query, 3)) {
                    echo "<ul>";
                    while ($rSe = $DB->fetch(3)) {
                        $arg = array();
                        $query = "SELECT * FROM results_argument WHERE results_sentence_id = '{$rSe['id']}'";
                        if ($DB->querynum($query, 4)) {
                            while ($rA = $DB->fetch(4)) {
                                $arg[$rA['id_token']] = $rA['argument'];
                            }
                        }
                        
                        $query = "SELECT * FROM sentence_tokens WHERE filename = '{$rSe['filename']}' AND sentence = '{$rSe['sentence']}' ORDER BY position";
                        if ($DB->querynum($query, 4)) {
                            echo "<li>";
                            echo "<a href='?deleteSentence={$rSe['id']}'>Delete</a> ";
                            echo "[{$rSe['id']} - {$rSe['filename']} - {$rSe['sentence']}] ";
                            $tokenNO = 0;
                            $completeSentence = array();
                            $Target = -1;
                            $TargetLemma = "";
                            $Frames = array();
                            while ($rSt = $DB->fetch(4)) {
                                $rSt['token'] = utf8_encode($rSt['token']);
                                $bgColor = $fgColor = "";
                                if ($rSe['id_token'] == $rSt['id']) {
	                                $Target = $tokenNO;
	                                $TargetLemma = utf8_encode($rSt['lemma']).".".$rSt['pos'];
                                    $bgColor = "000";
                                    $fgColor = "fff";
                                }
                                if ($myArg = $arg[$rSt['id']]) {
                                	$fName = $fe[$myArg]['name'];
                                	if (!isset($Frames[$fName])) {
                                		$Frames[$fName] = array();
                                	}
                                	$Frames[$fName][] = $tokenNO;
                                	// echo $fe[$myArg]['name'];
                                    $bgColor = $fe[$myArg]['bgColor'];
                                    $fgColor = $fe[$myArg]['fgColor'];
                                }
                                $completeSentence[] = trim($rSt['token']);
                                if ($bgColor) {
                                    echo "<span style='background-color: #$bgColor; color: #$fgColor;'>{$rSt['token']}</span> ";
                                }
                                else {
                                    echo "{$rSt['token']} ";
                                }
                                $tokenNO++;
                            }
                            echo "</li>";
                            fwrite($Fsent, implode(" ", $completeSentence)."\n");
                            fwrite($Ffrel, 1 + count($Frames));
                            fwrite($Ffrel, "\t" . $FrameName);
                            fwrite($Ffrel, "\t" . $TargetLemma);
                            fwrite($Ffrel, "\t" . $Target);
                            fwrite($Ffrel, "\t" . $completeSentence[$Target]);
                            fwrite($Ffrel, "\t" . $SentenceNO);
                            foreach ($Frames as $frameElement => $feIndexes) {
	                            fwrite($Ffrel, "\t" . str_replace(" ", "_", $frameElement));
                            	$min = min($feIndexes);
                            	$max = max($feIndexes);
                            	if ($min == $max) {
		                            fwrite($Ffrel, "\t" . $min);
                            	}
                            	else {
		                            fwrite($Ffrel, "\t" . $min . ":" . $max);
                            	}
                            }
							fwrite($Ffrel, "\n");
                            $SentenceNO++;
                            // echo "<li>".implode(" ", $completeSentence)." - TARGET: $Target - FE: ".var_export($Frames, true)."</li>";
                        }
                    }
                    echo "</ul>";
                }
                echo "</li>";
            }
            echo "</ul>";
        }
    }
}

fclose($Fsent);
fclose($Ffrel);

require_once("footer.inc.php");
