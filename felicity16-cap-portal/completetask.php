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

$sql = "UPDATE tasks SET complete=1 WHERE taskname='$taskname' and email='$email' and score='$score'";
if ($conn->query($sql) === TRUE) {
    echo "Task(s) completed succesfully";
} else {
    echo "Error: " . $sql . "<br>";
}

$result=$conn->query("SELECT * FROM scores WHERE email='$email'")->fetch_assoc()["score"];
$result=$result+$score;

$sql = "UPDATE scores SET score='$result' WHERE email='$email'";
if ($conn->query($sql) === TRUE) {
	echo "Updated score of user succesfully";
} else {
	echo "Error: " . $sql . "<br>";
}

$conn->close();

header("Location: login.php");
die();
?>