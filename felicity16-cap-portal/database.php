<?php

$servername = "localhost";
$username = "erilyth";
$password = "iiit123";
$dbname = "feli_CAP";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$qry_result = $conn->query("CREATE TABLE IF NOT EXISTS scores(
 email varchar(255) DEFAULT NULL,
 score int(11) DEFAULT NULL,
 PRIMARY KEY (email)
);");
if ($qry_result === TRUE){
	echo "New table created successfully <br>";
} else {
	echo "Error <br>";
}

$qry_result = $conn->query("CREATE TABLE IF NOT EXISTS users (
 perms int(11) DEFAULT NULL,
 name varchar(255) DEFAULT NULL,
 email varchar(255) DEFAULT NULL,
 passw varchar(255) DEFAULT NULL,
 phone int(15) DEFAULT NULL,
 college varchar(255) DEFAULT NULL,
 program varchar(255) DEFAULT NULL,
 year int(11) DEFAULT NULL,
 facebook varchar(255) DEFAULT NULL,
 whyapply varchar(400) DEFAULT NULL,
 aboutme varchar(400) DEFAULT NULL,
 organiseprogram varchar(400) DEFAULT NULL,
 PRIMARY KEY (email)
);");
if ($qry_result === TRUE) {
    echo "New table created successfully <br>";
} else {
    echo "Error <br>";
}

$qry_result = $conn->query("CREATE TABLE IF NOT EXISTS tasks (
 email varchar(255) DEFAULT NULL,
 taskname varchar(400) DEFAULT NULL,
 score int(11) DEFAULT NULL,
 complete int(11) DEFAULT NULL,
 PRIMARY KEY (taskname)
);");
if ($qry_result === TRUE) {
    echo "New table tasks exists/created successfully <br>";
} else {
    echo "Error cannot create table <br>";
}

?>