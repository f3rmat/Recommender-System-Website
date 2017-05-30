<?php
session_start();

if(isset($_SESSION['userid'])){
	echo "Login successful! Welcome ";
	echo $_SESSION['username'];

	echo'<a href="movies.php"> Movies </a>';

	echo'<a href="logout.php"> Click here to logout </a>';
}

else{
	echo'Please login to view this page';
	echo'<a href="login.php"> Click here to login </a>';

}

?>


<title>Home</title>