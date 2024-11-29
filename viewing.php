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
		$connectString = "mysql:host=$DB_HOST;port=3306;dbname=$DB_NAME";
		$pdoViewing = new PDO($connectString, $DB_USER, $DB_PASS);
	}
	catch (PDOException $e) { //Exception handling for database not found
		echo "Database connection unsuccessful.<br>";
		die($e->getMessage());
	}
    // Starting here by getting all of the unique creature names from the curated sightings list
	$sql = "SELECT DISTINCT creature_name FROM sighting_table";
	$sightingData = $pdoViewing->query($sql);
	$sightings = $sightingData->fetchAll(PDO::FETCH_ASSOC);


?>
<!-- Now, make a form that accepts a user to select which from the existing list of creatures they want to
look at. -->
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
    // Receive the input, which will always be in the database as it only outputs creatures in the database
    if ($_SERVER['REQUEST_METHOD'] == 'POST')
    {
        $displayCreature = htmlspecialchars($_POST["creatureSelect"]);

        // First check for the base condition, which will be the case on loading the website for the first time.
        if($displayCreature == "Select")
        {
            echo "<p>Please select a creature.</p>";
        }

        // Then check to see if the display all is selected
        elseif($displayCreature == "View all")
        {
            // If it is, select all and sort by the order in which the sightings were logged, most to least recent
            $sql = "SELECT * FROM sighting_table ORDER BY sighting_id DESC";
            $sightingDataDisplay = $pdoViewing->query($sql);
            $sightingDisplay = $sightingDataDisplay->fetchAll(PDO::FETCH_ASSOC);

            // At the end of each display, there's a line break so that the two entries don't get mixed up
            foreach($sightingDisplay as $sighting)
            {
                // Have conditionals for each entry to make sure that if a user didn't enter something, everything is
                // still formatted correctly
                echo "Creature name: <br>";
                echo (isset($sighting['creature_name'])) ? $sighting['creature_name']."<br><br>" : "N/A <br><br>";
                echo "Summary of sighting: <br>";
                echo (isset($sighting['summary'])) ? $sighting['summary']."<br><br>" : "N/A <br><br>";
                echo "Date sighted: <br>";
                echo (isset($sighting['date_sighted'])) ? $sighting['date_sighted']."<br><br>" : "N/A <br><br>";
                echo "Time sighted: <br>";
                echo (isset($sighting['time_of_day'])) ? $sighting['time_of_day']."<br><br>" : "N/A <br><br>";
                echo "Images: <br>";
                echo (isset($sighting['image'])) ? '<img src="data:image/jpeg;base64,'.base64_encode($sighting['image']).'"/>'. "<br><br>" : "N/A";

            }
        }

        // Finally, to catch everything else, search the table for only the creatures who have that name. The
        // internal logic is mostly the same.
        else
        {
            $sql = "SELECT * FROM sighting_table WHERE creature_name='{$displayCreature}'";
            $sightingDataDisplay = $pdoViewing->query($sql);
            $sightingDisplay = $sightingDataDisplay->fetchAll(PDO::FETCH_ASSOC);
            foreach($sightingDisplay as $sighting)
            {
                echo "Creature name: <br>";
                echo (isset($sighting['creature_name'])) ? $sighting['creature_name']."<br><br>" : "N/A <br><br>";
                echo "Summary of sighting: <br>";
                echo (isset($sighting['summary'])) ? $sighting['summary']."<br><br>" : "N/A <br><br>";
                echo "Date sighted: <br>";
                echo (isset($sighting['date_sighted'])) ? $sighting['date_sighted']."<br><br>" : "N/A <br><br>";
                echo "Time sighted: <br>";
                echo (isset($sighting['time_of_day'])) ? $sighting['time_of_day']."<br><br>" : "N/A <br><br>";
                echo "Images: <br>";
                echo (isset($sighting['image'])) ? '<img src="data:image/jpeg;base64,'.base64_encode($sighting['image']).'"/>'. "<br><br>" : "N/A";
            }
        }

    }






    $pdoViewing = null;
?>

</body>
</html>