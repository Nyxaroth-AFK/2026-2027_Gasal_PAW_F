<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h1>Posted data </h1>
    <?php
    foreach ($_POST as $field => $value){
    echo "($field)=> ($value) <br>";
    }
    ?>
</body>
</html>