<?php
$buah = array("Avocado", "Blueberry", "Cherry");
$tambahan = array("apel", "mangga", "semangka", "jeruk", "nanas");

for ($i = 0; $i < count($tambahan); $i++) {
    $buah[] = $tambahan[$i];
}

$arrlength = count($buah);
echo "Panjang array saat ini: " . $arrlength . "<br>";
for ($x = 0; $x < $arrlength; $x++) {
    echo $buah[$x] . "<br>";
}
echo "<br>";

$vegan = array("Carrot", "Broccoli", "Spinach");
$vegLength = count($vegan);

for ($x = 0; $x < $vegLength; $x++) {
    echo $vegan[$x] . "<br>";
}
?>