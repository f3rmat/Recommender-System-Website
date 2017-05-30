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
	<link rel="stylesheet" type="text/css" href="recommendation_style.css">

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

	<h2 style="text-align : center; font-family: Arial; margin-top: 50px;">Click on any one of the recommendation methods</h2>

<div  style="margin-top: 80px;">
	<span class="box2">
		<span style="margin-right: 100px; margin-left: 100px;"><a href="display_recom.php?id=1" id= "method1" style= "color: #fff;
			background: #337ab7;
			padding: 8px 17px 8px 17px;
			border: none;
			text-decoration: none;
			font-size: 15pt;
			font-family: Arial;"> Method 1 </a></span>
		<span style="margin-right: 100px; margin-left: 100px;"><a href="display_recom.php?id=2"  id= "method2" style= "color: #fff;
			background: #337ab7;
			padding: 8px 17px 8px 17px;
			border: none;
			text-decoration: none;
			font-size: 15pt;
			font-family: Arial;"> Method 2 </a></span>
		<span style="margin-right: 100px; margin-left: 100px;"><a href="display_recom.php?id=3"  id= "method3" style= "color: #fff;
			background: #337ab7;
			padding: 8px 17px 8px 17px;
			border: none;
			text-decoration: none;
			font-size: 15pt;
			font-family: Arial;"> Method 3 </a></span>
		<span style="margin-right: 100px; margin-left: 100px;"><a href="display_recom.php?id=4"  id= "method4" style= "color: #fff;
			background: #337ab7;
			padding: 8px 17px 8px 17px;
			border: none;
			text-decoration: none;
			font-size: 15pt;
			font-family: Arial;"> Method 4 </a></span>
	</span> 
	</div>
	</body>
</html>
