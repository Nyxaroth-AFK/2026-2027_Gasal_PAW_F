<?php
$height = array("Andy"=>"176", "Barry"=>"165", "Charlie"=>"170", "David"=>"180", "Ethan"=>"172", "Frank"=>"168", "George"=>"175", "Harry"=>"182");

foreach($height as $name => $h) {
    echo $name . " is " . $h . " cm tall.<br>";
}
echo "<br>";

$weight = array("Andy"=>"70", "Barry"=>"65", "Charlie"=>"75");
$keys = array_keys($weight); 
$length = count($keys);

for ($i = 0; $i < $length; $i++) {
    $name = $keys[$i];
    echo $name . " is " . $weight[$name] . " kg.<br>";
}
?>