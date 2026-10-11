<?php
$students = array(
    array("Alex", "220401", "0812345678"),
    array("Bianca", "220402", "0812345687"),
    array("Candice", "220403", "0812345665")
);

echo "Data awal:<br>";
echo "students = <br>";
foreach ($students as $i => $s) {
    echo '("' . implode('", "', $s) . '")';
    echo ($i < count($students) - 1) ? ",<br>" : "<br><br>";
}

array_push($students, 
    array("Daniel", "220404", "0812345611"),
    array("Elena", "220405", "0812345622"),
    array("Fiona", "220406", "0812345633"),
    array("Gabe", "220407", "0812345644"),
    array("Hannah", "220408", "0812345655")
);

// Menampilkan Data Setelah Ditambah
echo "Data setelah ditambah 5 data lain:<br>";
echo "students = <br>";
foreach ($students as $i => $s) {
    echo '("' . implode('", "', $s) . '")';
    echo ($i < count($students) - 1) ? ",<br>" : "<br><br>";
}

echo "<table border='1' cellspacing='0' cellpadding='5'>"; 
echo "<tr>
        <th align='left'>Name</th>
        <th align='left'>NIM</th>
        <th align='left'>Mobile</th>
      </tr>";

foreach ($students as $student) {
    echo "<tr>
            <td>" . $student[0] . "</td>
            <td>" . $student[1] . "</td>
            <td>" . $student[2] . "</td>
          </tr>";
}
echo "</table>";
?>