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

	$sql = "SELECT * FROM sighting_table";
	$sightingData = $pdoViewing->query($sql);
	$sightings = $sightingData->fetchAll(PDO::FETCH_ASSOC);
    $names = array();
    $names[] = "ooohhh";
    $names[] = "ahhhhh";
    echo "$names[0]";
    foreach($sightings as $nameCheck)
    {
        if(in_array($nameCheck['creature_name'], $names)
        {

        }
        else
        {
            $names[] = $sighting['creature_name']
        }
    }


	foreach($sightings as $sighting)
	{
	    if($sighting['creature_name'] == $names[$sighting])
	    {
	        $tempName = $sighting['creature_name'];
	        $tempDate = $sighting['date_sighted'];
	        $tempSumm = $sighting['summary'];
	        $tempTime = $sighting['time_of_day'];
	        $tempImg = $sighting['image'];
	        echo "$tempName". "<br>". "$tempDate". "<br>". "$tempSumm". "<br>". "$tempTime". "<br>". "$tempImg";
	    }

	}

    $pdoViewing = null;

?>
</body>
</html>