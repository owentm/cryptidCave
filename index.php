<!--
	//MySql database connection info
	$DB_HOST = 'localhost';
	$DB_USER = 'root';
	$DB_PASS = 'root';
	$DB_NAME = 'Cryptids';
	
	//Connecting to the Cryptids database
	try {
		$connectString = "mysql:host=$DB_HOST;port=3306;dbname=$DB_NAME";
		$pdo = new PDO($connectString, $DB_USER, $DB_PASS);
	}
	catch (PDOException $e) { //Exception handling for database not found
		echo "Database connection unsuccessful.<br>";
		die($e->getMessage());
	}
	
	//Closing PDO object
	$pdo = null;
-->

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Crypid Cave Home</title>
    <h1>Welcome to Crypid Cave!</h1>
</head>
<body>
<h2> I want to... </h2>
<a href="logging.html"><button> Log a sighting </button></a>
<a href="viewing.html"><button> Browse sightings </button></a>
</body>
</html>