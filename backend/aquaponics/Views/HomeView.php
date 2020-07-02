<?php
error_reporting(E_ALL); // for debugging
ini_set('display_errors', 1);
//testing db
$AquaponicsDB = new AquaponicsDB();
$rows = $AquaponicsDB->get_values(); //this gets all the values from readings table
//print_r($rows_values);
$history = $AquaponicsDB->get_history('2020-04-21 11:10:25', '2020-04-21 11:30:00');
print_r($history);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Aquaponics</title>
    <?php foreach ($rows as $row) : ?>
        <li><?php echo htmlspecialchars('Water level: ' . $row->{'water_level'}); ?></li>
    <?php endforeach ?>
</head>

<body>

</body>

</html>