<?php
$buah = array("Alpkat", "Blueberry", "Cery");
array_push($buah, "Durian", "Elderberry", "Fig", "Grape", "Honeydew");

echo 'buah = ("' . implode('", "', $buah) . '")<br>';
echo "Nilai dengan indeks tertinggi: " . end($buah) . "<br><br>";

unset($buah[1]);
$buah = array_values($buah);

echo "Data Blueberry dihapus.<br>";
echo 'fruits = ("' . implode('", "', $buah) . '")<br>';
echo "Nilai dengan indeks tertinggi: " . end($buah) . "<br>";
?>