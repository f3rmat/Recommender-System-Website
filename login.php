<?php
session_start();
if(isset($_SESSION['userid'])){
	header('location:home.php');

}
$conn = new mysqli("localhost", "root", "recommender", "majorproject");

if(isset($_POST['btn']))
{	

	//Get values passed from form in login.php file
	$username = $_POST['user'];
	$password = $_POST['pass'];

	//To prevent MySql injection
	$username = mysqli_real_escape_string($conn, $username);
	$password = mysqli_real_escape_string($conn, $password);

	$password = md5($password);

	// Check connection
	if ($conn->connect_error) {
    die("Connection failed: \n" . $conn->connect_error);
	} 

	//Query the database for user
	$sql = "SELECT * FROM users WHERE username = '$username' AND password = '$password' ";
	$result = $conn->query($sql); 
	$row = mysqli_fetch_array($result);

	if($row['username'] == $username && $row['password'] == $password){
		$_SESSION['userid'] = $row['userid'];
		$_SESSION['username'] = $username;
		header("location:home.php");    
	}

	else{
        $_SESSION['message']="Username and Password do not match"; 
	}

	$conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>
		login page
	</title>
	<link rel="stylesheet" type="text/css" href="login_style.css">
</head>
<body>
<?php
    if(isset($_SESSION['message']))
    {
         echo "<div id='error_msg'>".$_SESSION['message']."</div>";
         unset($_SESSION['message']);
    }
?>
	<div id="frm">
		<form action="login.php" method="POST">
			<p>
				<label>Username: </label>
				<input type="text" id="user" name="user" />
			</p>
			<p>
				<label>Password: </label>
				<input type="password" id="pass" name="pass" />
			</p>
			<br>
			<div>
				<a href="register.php" class="button">New User? Sign up!</a>
				<input type="submit" id="btn" name = "btn" value="Login" />
			</div>
		</form>
		


	</div>
</body>
</html>