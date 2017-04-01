<?php
session_start();

//connect to database

$conn = new mysqli("localhost","root","recommender","majorproject");

     
if(isset($_POST['btn']))
{

  $username=mysqli_real_escape_string($conn, $_POST['user']);
  $password=mysqli_real_escape_string($conn, $_POST['pass']);
  $password2=mysqli_real_escape_string($conn, $_POST['repass']); 

	if($password!=$password2){
	$_SESSION['message']="The two passwords do not match";   
	}  

	else if(strlen($password) < 8){
	$_SESSION['message']="The password length is less than 8";
	}

	else if(strlen($username) < 2){
	$_SESSION['message']="The Username is too small just like your dick";
	}

    else
     {      
     //Create User
     		$password = md5($password);
            $sql="INSERT INTO users (userid,username,password) VALUES (NULL,'$username','$password')";  
            if ($conn->query($sql) === TRUE) {
           		 $_SESSION['message']="You are now logged in"; 
           		 $_SESSION['username']=$username;
    			 header("location:login.php");    
			} 
    }
     $conn->close();
}
?>

<!DOCTYPE html>
<html>
<head>
	<title>
		Register page
	</title>
	<link rel="stylesheet" type="text/css" href="register_style.css">
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
		<form action="register.php" method="POST">
			<p>
    			<input placeholder = 'Username' type='text' id='user' name="user" />		
			</p>
			<p>
				<input placeholder = "Password" type="password" id="pass" name="pass" />
			</p>
			<p>
				<input placeholder = "Re-type Password" type="password" id="repass" name="repass" />
			</p>
			<br>
			<div>
				<input type="submit" id="btn" name= 'btn' value="Sign Up" />
			</div>
		</form>
	</div>
</body>
</html>