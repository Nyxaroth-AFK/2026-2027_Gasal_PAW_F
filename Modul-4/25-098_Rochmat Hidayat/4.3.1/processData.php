<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Process Data Validation</title>
</head>
<body>
    <h1>Posted data</h1>
    <?php
    require 'validate.inc.php';

    $errors = array();
    validateName($errors, $_POST, 'surname');

    if ($errors) {
        echo 'Errors:<br/>';
        foreach ($errors as $field => $error) {
            echo "$field $error<br/>";
        }
    } else {
        echo 'Data OK!';
    }
    ?>
</body>
</html>