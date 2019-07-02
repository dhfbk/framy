<?php

$File = "txt/Messaggi_WA_Brambi_clean.txt";

$F = file($File);
$i = 0;
foreach ($F as $row) {
    echo "<p>" . (++$i) . " " . trim($row) . "</p>\n";
}
