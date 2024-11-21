<?php
	session_start();
	
	//MySql database connection info
	$DB_HOST = 'localhost';
	$DB_USER = 'root';
	$DB_PASS = 'root';
	$DB_NAME = 'Cryptids';
	
	//Connecting to the Cryptids database
	try {
		$connectString = "mysql:host=$DB_HOST;port=3305;dbname=$DB_NAME";
		$pdoSighting = new PDO($connectString, $DB_USER, $DB_PASS);
	}
	catch (PDOException $e) { //Exception handling for database not found
		echo "Database connection unsuccessful.<br>";
		die($e->getMessage());
	}
	
	//Changing file location
	$path = ""; //Initializing new file path variable for later use
	
	if($_FILES['image']['error'] == 0) { //Checking to ensure a file was uploaded without error
		$fname = $_FILES['image']['name'];
		$ftemp = $_FILES['image']['tmp_name'];
		
		//Ensuring files with similar names will not be overwritten
		$append = 0;
		$filesplit = "";
		
		//Splitting file name and extension apart in order to create unique file name below
		if(file_exists("uploads/" . basename($fname))) {
			$filesplit = explode(".", $fname);
		}
		
		//Continue updating file name until unique name found
		while(file_exists("uploads/" . basename($fname))) {
			//Appending new suffix number each time file names conflict
			$append += 1;
			$namesplit = $filesplit[0];
			$fname = $namesplit . "(" . $append . ")" . "." . $filesplit[1]; //Recreating file name with extension
		}
		
		//Creating new filepath, moving uploaded file to set location
		$path = "uploads/" . basename($fname);
		move_uploaded_file($ftemp, $path);
	}
	
	//Updating sightings table if any new sightings have been logged
	if(isset($_POST['submitSighting'])) {
		//Base query string for inserting 4 values into sighting_table
		$query = "INSERT INTO sighting_table (creature_name, summary, date_sighted, time_of_day) VALUES (?, ?, ?, ?)";
		
		//Adding another value if an image was submitted
		if($_FILES['image']['error'] == 0) {
			$query = "INSERT INTO sighting_table (creature_name, summary, date_sighted, time_of_day, image) VALUES (?, ?, ?, ?, ?)";
		}
	
		//Binding user-entered data to values to be inserted into sighting_table
		$statement = $pdoSighting->prepare($query);
		$statement->bindValue(1, $_POST['creature'], PDO::PARAM_STR); //Creature name
		$statement->bindValue(2, $_POST['desc'], PDO::PARAM_LOB); //Description
		$statement->bindValue(3, $_POST['dateFound'], PDO::PARAM_STR); //Date
		$statement->bindValue(4, $_POST['timesight'], PDO::PARAM_STR); //Time of day
		
		//Binding image to value if an image was submitted
		if($_FILES['image']['error'] == 0) {
			$statement->bindValue(5, $path, PDO::PARAM_STR);
		}
		
		$statement->execute();
	}
	
	//Closing PDO object
	$pdoSighting = null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Cryptid Cave Logging</title>
    <h2><a href="index.php"><button> Go home </button></a></h2>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="flex-container">
        <div>
            <h1>Log A Sighting</h1>
			<form id="sightingLog" action="logging.php" method="post" enctype="multipart/form-data">
				<label for="creaturelog">Name of creature:</label>
				<br>
				<input type="text" id="creaturelog" name="creature">

				<br>
				<br>

				<label for="datefound"> Date seen: </label>
				<br>
				<input type="date" id="datefound" name="dateFound">
				<br>
				<br>

				<label for="timesight">Time of day seen:</label>
				<br>
				<select name="timesight" id="timesight" form="sightingLog">
					<option value="Early morning">Early morning</option>
					<option value="Morning">Morning</option>
					<option value="Noon">Noon</option>
					<option value="Evening">Evening</option>
					<option value="Midnight">Midnight </option>
				</select>

				<br>
				<br>

				<label for="imageofcreature"> Image (if applicable) of encounter: </label>
				<br>
				<input type="file" id="imageofcreature" name="image">

				<br>
				<br>

				<label for="descriptionofevent"> Short description of event: </label>
				<br>
				<textarea id="descriptionofevent" name="desc"></textarea>

				<br>
				<br>

				<input type="submit" id="creaturesubmit" value="Submit sighting" name="submitSighting">
			</form>
            </div>
            <div>
            <img src="neuteredlilguy.png">
        </div>
    </div>
</body>
</html>