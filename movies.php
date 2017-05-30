<?php
session_start();

if(empty($_SESSION['userid'])){

	header('location:login.php');
	
}

?>


<html>
	<head>
	<link rel="stylesheet" type="text/css" href="movies_style.css">
	<title>
		Movies
	</title>
	</head>

	<body>
	<header id="userheader">

		<span class="box" style=" width: 320px; margin-right: 300px; margin-left: 10px;"><?php echo '<h1 style="font-family: Arial;"> Welcome ' .$_SESSION['username'].'</h1>' ?></span>
		<span class = "box1"> 
			<span style="margin-right: 25px;"><a href="recommendation.php" id= "rec" style= "color: #fff;
			background: #337ab7;
			padding: 8px 17px 8px 17px;
			border: none;
			text-decoration: none;
			font-size: 15pt;
			font-family: Arial;"> Get Recommendations </a></span> 
			<span style="margin-right: 25px;"><a href="ratemovies.php"  id= "rate_button"> Rate more movies </a></span>
			<span><a href="logout.php"  id= "logout_button"> Click here to logout </a></span>
		</span>
	</header>

	<h1 style="text-align: center; 	font-family: Arial;">Movies you have already rated</h1>
	<?php

		$conn = new mysqli("localhost", "root", "recommender", "majorproject");
		$user_id_query = $_SESSION['userid'];
		$sql = "SELECT * FROM ratings WHERE userid = '$user_id_query'";
	
		$result = $conn->query($sql); 
		$row = mysqli_fetch_array($result);

		$movie_array = array(); //movie names
		$rating_array = array(); //ratings for the movies

		array_push($movie_array, $row["movieid"]);
		array_push($rating_array, $row["rating"]);



		while($row = $result->fetch_assoc()) {
		array_push($movie_array, $row["movieid"]);
		array_push($rating_array, $row["rating"]);

    	}

    	//print_r($movie_array);

    	$arrlength = count($movie_array);

		for($x = 0; $x < $arrlength; $x++) {
    	$sql2 = "SELECT moviename FROM moviedata WHERE movieid = '$movie_array[$x]'";
		$result2 = $conn->query($sql2); 
		$row2 = mysqli_fetch_array($result2);
    	$movie_array[$x] = $row2['moviename'];
		}


		echo '<table align="center" border="1"><tr><td style="text-align:center;"><b>Movie Name</b></td><td><b>Rating</b></td></tr>';

		for($x = 0; $x < $arrlength; $x++) {
		echo '<tr>';
		echo '<td>' . $movie_array[$x] . '</td>';
		echo '<td>' . $rating_array[$x] . '</td>';
		echo '</tr>';
    	//echo $movie_array[$x] . ' ' . $rating_array[$x]. '<br>' ;
    	}

    	echo '</table>';
    
    	$conn->close();
	?>
	
	</body>
</html>
