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

		<span class="box" style=" width: 320px; margin-right: 777px; margin-left: 10px;"><?php echo '<h1 style="font-family: Arial;"> Welcome ' .$_SESSION['username'].'</h1>' ?></span>
		<span class = "box1"> 
			
			<span><a href="logout.php"  id= "logout_button"> Click here to logout </a></span>
		</span>
	</header>

	<div id="search_div">
		<form name="form" action="ratemovies.php" method="post">
    		<input id = "search_box" name = "search_box" placeholder ="Type Search Query Here" type="text" autofocus/>
    		 <input id = "search-icon" value="Submit" type="image" name="btn" src="search-icon.png"/>
    	<br>
    	          
          <input type = "radio" name = "search_type" value = "Movie" checked >
          By Movie Name 
          <input type = "radio" name = "search_type" value = "Genre" >
    	  By Genre

        </form>
	</div>

	<?php
	$conn = new mysqli("localhost","root","harshit25","majorproject");

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
				$search_term = mysqli_real_escape_string($conn, $_POST['search_box']);
				$sql = "SELECT moviename FROM moviedata ";
				$result = $conn->query($sql); 
				$row = mysqli_fetch_array($result);
				$flag = 0;

				if (strpos(strtolower($row["moviename"]), strtolower($search_term)) !== false) {
	    				echo $row["moviename"].'<br>';
	    				$flag = 1;
					}
				while($row = $result->fetch_assoc()) {

					if (strpos(strtolower($row["moviename"]), strtolower($search_term)) !== false) {
	    				echo $row["moviename"].'<br>';
	    				$flag = 1;
					}

	    		}


	    		if($flag==0){
	    		echo"<script>alert('No movies found!');</script>";
	    		}

			}

			else
			{
				$search_term = mysqli_real_escape_string($conn, $_POST['search_box']);
				$sql = "SELECT moviename, genre FROM moviedata";
				$result = $conn->query($sql); 
				$row = mysqli_fetch_array($result);
				$flag = 0;

				if (strpos(strtolower($row["genre"]), strtolower($search_term)) !== false) {
	    				echo $row["moviename"].'<br>';
	    				$flag = 1;
					}
				while($row = $result->fetch_assoc()) {

					if (strpos(strtolower($row["genre"]), strtolower($search_term)) !== false) {
	    				echo $row["moviename"].'<br>';
	    				$flag = 1;
					}

	    		}


	    		if($flag==0){
	    		echo"<script>alert('No movies found!');</script>";
	    		}
			}
			

		}

	}

	$conn->close();
	?>
	</body>
</html>
