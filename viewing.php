<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="styles.css">
<head>
    <meta charset="UTF-8">
    <title>Cryptid Cave Viewing</title>
    <h2><a href="index.php"><button> Go home </button></a></h2>
</head>
<body>
<h1>Viewing Page Placeholder</h1>
<?php
	session_start();

	//MySql database connection info
	$DB_HOST = 'localhost';
	$DB_USER = 'root';
	$DB_PASS = 'root';
	$DB_NAME = 'Cryptids';

	//Connecting to the Cryptids database
	try
	{
		$connectString = "mysql:host=$DB_HOST;port=3305;dbname=$DB_NAME";
		$pdoViewing = new PDO($connectString, $DB_USER, $DB_PASS);
	}
	catch (PDOException $e) { //Exception handling for database not found
		echo "Database connection unsuccessful.<br>";
		die($e->getMessage());
	}

	$sql = "SELECT DISTINCT creature_name FROM sighting_table";
	$sightingData = $pdoViewing->query($sql);
	$sightings = $sightingData->fetchAll(PDO::FETCH_ASSOC);


?>
<form method="POST">
<select name="creatureSelect">
    <option value = "Select" selected> - </option>
   <?php foreach($sightings as $name): ?>
        <option value="<?php echo $name['creature_name']; ?>">
        <?php echo $name['creature_name']; ?>
        </option>
        <?php endforeach; ?>
   <option value = "View all"> View all </option>
</select>
<input type="submit" value="Submit">
</form>
<?php
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $displayCreature = htmlspecialchars($_POST["creatureSelect"]);


        if($displayCreature == "Select")
        {
            echo "<p>Please select a creature.</p>";
        }
        elseif($displayCreature == "View all")
        {
            $sql = "SELECT * FROM sighting_table ORDER BY sighting_id DESC";
            $sightingDataDisplay = $pdoViewing->query($sql);
            $sightingDisplay = $sightingDataDisplay->fetchAll(PDO::FETCH_ASSOC);
            foreach($sightingDisplay as $sighting)
            {
                echo $sighting['creature_name']. "<br><br>". $sighting['summary']. "<br><br>". $sighting['date_sighted'];
                echo $sighting['time_of_day']. "<br><br>". $sighting['image'];

            }
        }
        else
        {
            $sql = "SELECT * FROM sighting_table WHERE creature_name='{$displayCreature}'";
            $sightingDataDisplay = $pdoViewing->query($sql);
            $sightingDisplay = $sightingDataDisplay->fetchAll(PDO::FETCH_ASSOC);
            foreach($sightingDisplay as $sighting)
            {
                echo $sighting['creature_name']. "<br><br>". $sighting['summary']. "<br><br>". $sighting['date_sighted'];
                echo $sighting['time_of_day']. "<br><br>". $sighting['image'];

            }
        }

    }






    $pdoViewing = null;
?>

</body>
</html>