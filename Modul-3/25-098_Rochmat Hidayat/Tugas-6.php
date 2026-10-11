<?php
$arr1 = array("A");
array_push($arr1, "B");
echo "Hasil array push: " . implode("", $arr1) . "<br>";

$arr_a = array("A", "B");
$arr_b = array("C");
$merged = array_merge($arr_a, $arr_b);
echo "Hasil array merge: " . implode("", $merged) . "<br>";

$arr_val = array("x"=>1, "y"=>2);
$vals = array_values($arr_val);
echo "Hasil array values: " . implode("", $vals) . "<br>";

$arr_search = array("A", "B", "C");
$index = array_search("B", $arr_search);
echo "Hasil array search: " . $index . "<br>";

$arr_filter = array(0, 1, false, 2, 3, "array");
$filtered = array_filter($arr_filter);
echo "Hasil array filter: " . implode(" ", $filtered) . "<br>";

$arr_sort = array(3, 1, 2);
sort($arr_sort);
echo "Hasil sort: " . implode("", $arr_sort) . "<br>";

$arr_rsort = array(3, 1, 2);
rsort($arr_rsort);
echo "Hasil rsort: " . implode("", $arr_rsort) . "<br>";

function printAssoc($arr) {
    $out = array();
    foreach($arr as $k => $v) { $out[] = "$k => $v"; }
    return implode(", ", $out) . ",";
}

$arr_assoc = array("Peter"=>35, "Ben"=>37, "Joe"=>43);
$asort_arr = $arr_assoc; asort($asort_arr);
echo "Hasil asort: " . printAssoc($asort_arr) . "<br>";

$ksort_arr = $arr_assoc; ksort($ksort_arr);
echo "Hasil ksort: " . printAssoc($ksort_arr) . "<br>";

$arsort_arr = $arr_assoc; arsort($arsort_arr);
echo "Hasil arsort: " . printAssoc($arsort_arr) . "<br>";

$krsort_arr = $arr_assoc; krsort($krsort_arr);
echo "Hasil krsort: " . printAssoc($krsort_arr) . "<br>";
?>