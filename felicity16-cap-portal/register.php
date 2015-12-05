<?php

require 'database.php';

echo "Name: ".$_POST['name']."<br>";
echo "Email ID: ".$_POST['email']."<br>";
echo "Phone Number ".$_POST['phone']."<br>";
echo "College/University Name: ".$_POST['college']."<br>";
echo "Program of Study: ".$_POST['program']."<br>";
echo "Year Of Study: ".$_POST['year']."<br>";
echo "Facebook Profile Link: ".$_POST['facebook']."<br>";
echo "Why do you want to apply for the campus ambassador program?: ".$_POST['whyapply']."<br>";
echo "Write about you in short: ".$_POST['aboutme']."<br>";
echo "Did you organise any event/program in your school or your college ?: ".$_POST['organiseprogram']."<br>";

$name=$_POST['name'];
$email=$_POST['email'];
$passw=hash("sha256",$_POST['passw']);
$phone=$_POST['phone'];
$college=$_POST['college'];
$program=$_POST['program'];
$year=$_POST['year'];
$facebook=$_POST['facebook'];
$whyapply=$_POST['whyapply'];
$aboutme=$_POST['aboutme'];
$organiseprogram=$_POST['organiseprogram'];

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlcheck="SELECT * from users where email='$email'";
$result = $conn->query($sqlcheck);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "name: " . $row["name"]. " - Email: " . $row["email"]."<br>";
    }
} else {
    echo "0 results";
    $sql = "INSERT INTO users VALUES (0,'$name', '$email', '$passw', '$phone', '$college', '$program', '$year', '$facebook', '$whyapply', '$aboutme', '$organiseprogram')";
	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}

	$sql = "INSERT INTO scores VALUES ('$email',0)";
	if ($conn->query($sql) === TRUE) {
	    echo "New record created successfully";
	} else {
	    echo "Error: " . $sql . "<br>" . $conn->error;
	}
}

$conn->close();

header("Location: login.html");
die();

?>