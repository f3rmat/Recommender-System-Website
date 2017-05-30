<?php
session_start();

if(empty($_SESSION['userid'])){

	header('location:login.php');
	
}

?>
<html>
	<head>
	<title>
		Rate Movies!
	</title>
	<link rel="stylesheet" type="text/css" href="ratemovies_style.css">

	</head>

	<body>
	<header id="userheader">

		<span class="box" style=" width: 320px; margin-right: 650px; margin-left: 10px;"><?php echo '<h1 style="font-family: Arial;"> Welcome ' .$_SESSION['username'].'</h1>' ?></span>
		<span class = "box1"> 
			<span style="margin-right: 25px;"><a href="movies.php" id= "rec" style= "color: #fff;
			background: #337ab7;
			padding: 8px 17px 8px 17px;
			border: none;
			text-decoration: none;
			font-size: 15pt;
			font-family: Arial;"> Go Back </a></span>
			<span><a href="logout.php"  id= "logout_button"> Click here to logout </a></span>
		</span>
	</header>

	<div id="search_div">
		<form name="form" action="ratemovies.php" method="post">

		<?php
		 if(isset($_POST['btn']))
		 {
		 echo '<input id = search_box name = search_box type=text value="'.$_POST['search_box'].'">';
		 echo "</input>";
		 }

		 else
		 {
		 	echo "<input id = 'search_box' name = 'search_box' placeholder ='Type Search Query Here' type='text' autofocus/>";
		 }
		?>

    		<!--<input id = "search_box" name = "search_box" placeholder ="Type Search Query Here" type="text" autofocus/>-->
    		 <input id = "search-icon" value="Submit" type="image" name="btn" src="search-icon.png"/>
    	<br>
    	          
          <input type = "radio" name = "search_type" value = "Movie" checked >
          By Movie Name 
          <input type = "radio" name = "search_type" value = "Genre" >
    	  By Genre

        </form>
	</div>

	<?php
	$conn = new mysqli("localhost","root","recommender","majorproject");

	if(isset($_POST['btn']))
	{	
		

		if(strlen($_POST['search_box']) <=2)
		{
			echo"<script>alert('The length of the search query is too less');</script>";
		}

		
		else if(isset($_POST['search_type']))
		{	
			if($_POST['search_type']=='Movie')
			{	
				//echo $_POST['search_box'];
				echo '<span style = " margin-left: 40%; margin-right: 25%;"> <p style= "display : inline-block;"><b>Legend:</b>&nbsp;&nbsp;&nbsp;&nbsp;<p style = "color : red; display : inline-block;"><u>Unrated</u>&nbsp;&nbsp;&nbsp;&nbsp;</p> <p style = "color : green; display : inline-block;"><u>Rated</u></p> </p> </span>';
				echo '<table align="center" border="1"><tr><td style="text-align:center;"><b>Movie Name</b></td><td><b>Average Rating</b></td>
				<td><b>Number of Ratings</b></td></tr>';

				$search_term = $_POST["search_box"];
				//upar waley mein mysqli_real_escape_string nahi lena hai Mummy's ko search nahi karega
				

				$sql = "SELECT movieid, moviename, no_of_users_rated, avg_rating FROM moviedata ORDER BY avg_rating DESC, no_of_users_rated DESC";
				$result = $conn->query($sql); 
				$row = mysqli_fetch_array($result);
				$flag = 0;
				$x = $_SESSION["userid"];

				
				if (strpos(strtolower($row["moviename"]), strtolower($search_term)) !== false) {
					    echo '<tr>';
					    $y = $row["movieid"];
					    
					    $sql2 = "SELECT * FROM ratings WHERE userid = '$x' AND movieid = '$y' ";
					    $result2 = $conn->query($sql2);
					    if (mysqli_num_rows($result2)==0)
					    	echo "<td><a target='_blank'style='color: red;' href='enter_rating.php?id=";
						else
							echo "<td><a target='_blank'style='color: green;' href='enter_rating.php?id=";
					    
					    echo $row["movieid"];
					    echo "'>";
					    echo $row["moviename"];
					    echo "</a></td>";
					    echo '<td>' . $row["avg_rating"] . '</td>';
					    echo '<td>' . $row['no_of_users_rated'] . '</td>';
					    echo '</tr>';
	    				$flag = 1;
					}
				while($row = $result->fetch_assoc()) {

					if (strpos(strtolower($row["moviename"]), strtolower($search_term)) !== false) {
	    				echo '<tr>';

						$y = $row["movieid"];
					    
					    $sql3 = "SELECT * FROM ratings WHERE userid = '$x' AND movieid = '$y' ";
					    $result3 = $conn->query($sql3);
					    if (mysqli_num_rows($result3)==0)
					    	echo "<td><a target='_blank'style='color: red;' href='enter_rating.php?id=";
						else
							echo "<td><a target='_blank'style='color: green;' href='enter_rating.php?id=";					    
					    echo $row["movieid"];
					    echo "'>";
					    echo $row["moviename"];
					    echo "</a></td>";
					    echo '<td>' . $row["avg_rating"] . '</td>';
					    echo '<td>' . $row['no_of_users_rated'] . '</td>';
					    echo '</tr>';
	    				$flag = 1;
					}

	    		}

	    		echo '</table>';
	    		if($flag==0){
	    		echo"<script>alert('No movies found!');</script>";
	    		}

			}

			else
			{	
				echo '<table align="center" border="1"><tr><td style="text-align:center;"><b>Movie Name</b></td><td><b>Average Rating</b></td>
					<td><b>Number of Ratings</b></td></tr>';

				$search_term = mysqli_real_escape_string($conn, $_POST['search_box']);
				$sql = "SELECT movieid, moviename, genre, no_of_users_rated, avg_rating FROM moviedata  ORDER BY avg_rating DESC, no_of_users_rated DESC";
				$result = $conn->query($sql); 
				$row = mysqli_fetch_array($result);
				$flag = 0;
				$x = $_SESSION["userid"];

				if (strpos(strtolower($row["genre"]), strtolower($search_term)) !== false) {
	    				echo '<tr>';
					    $y = $row["movieid"];
					    
					    $sql2 = "SELECT * FROM ratings WHERE userid = '$x' AND movieid = '$y' ";
					    $result2 = $conn->query($sql2);
					    if (mysqli_num_rows($result2)==0)
					    	echo "<td><a target='_blank'style='color: red;' href='enter_rating.php?id=";
						else
							echo "<td><a target='_blank'style='color: green;' href='enter_rating.php?id=";
					    
					    echo $row["movieid"];
					    echo "'>";
					    echo $row["moviename"];
					    echo "</a></td>";
					    echo '<td>' . $row["avg_rating"] . '</td>';
					    echo '<td>' . $row['no_of_users_rated'] . '</td>';
					    echo '</tr>';
	    				$flag = 1;
					}
				while($row = $result->fetch_assoc()) {

					if (strpos(strtolower($row["genre"]), strtolower($search_term)) !== false) {
	    				echo '<tr>';
					    $y = $row["movieid"];
					    
					    $sql2 = "SELECT * FROM ratings WHERE userid = '$x' AND movieid = '$y' ";
					    $result2 = $conn->query($sql2);
					    if (mysqli_num_rows($result2)==0)
					    	echo "<td><a target='_blank'style='color: red;' href='enter_rating.php?id=";
						else
							echo "<td><a target='_blank'style='color: green;' href='enter_rating.php?id=";
					    
					    echo $row["movieid"];
					    echo "'>";
					    echo $row["moviename"];
					    echo "</a></td>";
					    echo '<td>' . $row["avg_rating"] . '</td>';
					    echo '<td>' . $row['no_of_users_rated'] . '</td>';
					    echo '</tr>';
	    				$flag = 1;
					}

	    		}


	    		if($flag==0){
	    		echo"<script>alert('No movies found!');</script>";
	    		}
	    		echo '</table>';

			}


			

		}

	}

	$conn->close();
	?>
	</body>
</html>