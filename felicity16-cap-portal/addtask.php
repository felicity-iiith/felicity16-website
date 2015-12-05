<?php

require 'database.php';

$taskname=$_POST['taskname'];
$email=$_POST['email'];
$score=$_POST['score'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result=$conn->query("SELECT * FROM users WHERE email='$email'");
if ($result->num_rows > 0) {
	$sql = "INSERT INTO tasks VALUES ('$email','$taskname','$score',0)";
	if ($conn->query($sql) === TRUE) {
	    echo "New task created successfully";
	} else {
	    echo "Error: " . $sql . "<br>";
	}
} else {
	echo "Error1 <br>";
}	

$conn->close();

header("Location: login.php");
die();
?>