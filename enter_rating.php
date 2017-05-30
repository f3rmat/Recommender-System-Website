<?php
	session_start();
	if (isset($_POST['btn'])){
	//$_SESSION['enter_rating_id'] is the movie id
	$rating = -1;	
	if($_POST['star']=='star-10'){$rating = 10;}
	else if($_POST['star']=='star-9'){$rating = 9;}
	else if($_POST['star']=='star-8'){$rating = 8;}
	else if($_POST['star']=='star-7'){$rating = 7;}
	else if($_POST['star']=='star-6'){$rating = 6;}
	else if($_POST['star']=='star-5'){$rating = 5;}
	else if($_POST['star']=='star-4'){$rating = 4;}
	else if($_POST['star']=='star-3'){$rating = 3;}
	else if($_POST['star']=='star-2'){$rating = 2;}
	else if($_POST['star']=='star-1'){$rating = 1;}

	echo $rating;
	//echo $_SESSION["userid"];
	//echo $_SESSION["enter_rating_id"];
	$x = $_SESSION["userid"];
	$y = $_SESSION["enter_rating_id"];

	$conn = new mysqli("localhost","root","recommender","majorproject");
    
    $sql2 = "INSERT INTO ratings (userid,movieid,rating) VALUES ('$x','$y','$rating')";

	if($conn->query($sql2)===TRUE){
		//echo "nice1";
	}

	$sql3 = " SELECT no_of_users_rated, avg_rating FROM moviedata WHERE movieid = '$y' ";
	$result = $conn->query($sql3); 
	$row = mysqli_fetch_array($result);
	$a = $row["no_of_users_rated"];
	$b = $row["avg_rating"];

	$b = ( 1.0*($row["avg_rating"])*($row["no_of_users_rated"]) + $rating)/($row["no_of_users_rated"] + 1.0); 
	$a = $a + 1;

	$sql4 = "UPDATE moviedata SET no_of_users_rated = '$a', avg_rating = '$b' WHERE movieid = '$y' ";
	
	if($conn->query($sql4)===TRUE){
		//echo "nice2";
	}

	$conn->close();
	echo "<script>window.close();</script>";

	}
	unset($_SESSION['enter_rating_id']);

	
?>
<!DOCTYPE html>
<html>
	<head>
		<title>Enter Rating</title>
		<link rel="stylesheet" type="text/css" href="enter_rating_style.css">
		<link rel="stylesheet" href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css">
	</head>
	
	<body>
		<?php
		$url = $_SERVER['REQUEST_URI'];
		$split = -1;
		for($x = 0; $x < strlen($url); $x++){
			if($url[$x]=='='){
				$split = $x;
				break;
			}
		}
		$query_id = "";
		for($x = $split+1; $x < strlen($url); $x++){
		$query_id = $query_id.$url[$x];
		}

		$_SESSION['enter_rating_id'] = $query_id;

		$conn = new mysqli("localhost","root","recommender","majorproject");
		$sql = "SELECT moviename FROM moviedata WHERE movieid = $query_id";
		$result = $conn->query($sql); 
		$row = mysqli_fetch_array($result);
		
		echo "<h1 align = center>";
		echo $row["moviename"];
		echo "</h1>";

		echo "<h3 align = center>";
		echo "Your rating: ";
		echo "</h3>";
		$conn->close();		
		?>

	<div class="stars" style="margin:auto; width:940px;">
		<form action="enter_rating.php" method="POST">	


			<input class="star star-5" id="star-10" type="radio" value="star-10" name="star"/>
		    <label class="star star-5" for="star-10"></label>
		    <input class="star star-4" id="star-9" type="radio" value="star-9" name="star"/>
		    <label class="star star-4" for="star-9"></label>
		    <input class="star star-3" id="star-8" type="radio" value="star-8" name="star"/>
		    <label class="star star-3" for="star-8"></label>
		    <input class="star star-2" id="star-7" type="radio" value="star-7" name="star"/>
		    <label class="star star-2" for="star-7"></label>
		    <input class="star star-2" id="star-6" type="radio" value="star-6" name="star"/>
		    <label class="star star-2" for="star-6"></label>
		    <input class="star star-2" id="star-5" type="radio" value="star-5" name="star"/>
		    <label class="star star-2" for="star-5"></label>
		    <input class="star star-2" id="star-4" type="radio" value="star-4" name="star"/>
		    <label class="star star-2" for="star-4"></label>
		    <input class="star star-1" id="star-3" type="radio" value="star-3" name="star"/>
		    <label class="star star-1" for="star-3"></label>
		    <input class="star star-1" id="star-2" type="radio" value="star-2" name="star"/>
		    <label class="star star-1" for="star-2"></label>
		    <input class="star star-1" id="star-1" type="radio" value="star-1" name="star"/>
		    <label class="star star-1" for="star-1"></label>
		    <br><br><br><br>

		   	<input style = "margin-left:640px;" type="submit" id="btn" name = "btn" value="Submit" />

		  </form>
	</div>


	</body>
</html>