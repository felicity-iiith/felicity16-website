<?php

session_start();
require 'database.php';

if( isset($_POST['passw']) ){
	$passw=hash("sha256",$_POST['passw']);
	$email=$_POST['email'];
	$_SESSION["passw"] = $passw;
	$_SESSION["email"] = $email;
}

else{
	$passw=$_SESSION["passw"];
	$email=$_SESSION["email"];
}

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlcheck="SELECT * from users where email='$email' and passw='$passw'";
$result = $conn->query($sqlcheck);

if ($result->num_rows > 0) {
    // output data of each row
    echo "LOGGED IN AS".$email."<br>";
    $score=$conn->query("SELECT * from scores where email='$email'")->fetch_assoc()["score"];
    echo "YOUR SCORE: ".$score."<br>";
	if($result->fetch_assoc()["perms"]==1){
		?>
		<form action="addtask.php" method="post">
			Task Detail: <input type="text" name="taskname"></input><br />
			Email Assigned To: <input type="text" name="email"></input><br />
			Task Score: <input type="text" name="score"></input><br />
			<input type="submit" value="Add Task"/>
		</form>
		<h3>Task List (Open)</h3>
		<?php
		$sqltasklist="SELECT * from tasks where complete=0";
		$result = $conn->query($sqltasklist);

		if ($result->num_rows > 0) {
		    // output data of each row
		    while($row = $result->fetch_assoc()) {
		        echo "Email: " . $row["email"]. " - Task Detail: " . $row["taskname"]."<br>";
		        ?>
		        <form action="completetask.php" method="post">
					<input type="hidden" name="taskname" value="<?php echo $row["taskname"] ?>"></input>
					<input type="hidden" name="email" value="<?php echo $row["email"] ?>"></input>
					<input type="hidden" name="score" value="<?php echo $row["score"] ?>"></input>
					<input type="submit" value="Complete Task"/>
				</form>
		        <?php
		    }
		} else {
		    echo "0 results";
		}
	}
	echo "Your Tasks <br>";
	$sqlcheck2="SELECT * from tasks where email='$email' and complete=0";
	$result = $conn->query($sqlcheck2);

	if ($result->num_rows > 0) {
	    // output data of each row
	    while($row = $result->fetch_assoc()) {
	        echo "Email: " . $row["email"]. " - Task Detail: " . $row["taskname"]."<br>";
	    }
	} else {
	    echo "0 results";
	}

} else {
    echo "0 results";
    echo "LOGIN FAILED";
}

$conn->close();

?>