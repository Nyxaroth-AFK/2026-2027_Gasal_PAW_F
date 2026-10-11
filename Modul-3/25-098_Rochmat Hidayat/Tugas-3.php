<?php
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170");
$height["David"] = "180";
$height["Ethan"] = "172";
$height["Frank"] = "168";
$height["George"] = "175";
$height["Harry"] = "182";

echo "Nilai dengan indeks terakhir: " . end($height) . "<br>";

unset($height["Barry"]);
echo "Nilai dengan indeks terakhir setelah dihapus: " . end($height) . "<br><br>";

$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");
$keys = array_keys($weight);

echo "Data kedua: " . $weight[$keys[1]] . "<br>";
?>