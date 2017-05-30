<?php
$conn = new mysqli("localhost", "root", "recommender", "majorproject");
        //https://www.w3schools.com/sql/sql_alter.asp
	$sql = "SELECT * FROM ratings";
	
	$result = $conn->query($sql); 
	$row = mysqli_fetch_array($result);

	$rating_count = array();
	$rating_count = array_fill(0,164980, 0);


	$rating_count[$row["movieid"]]++;

	while($row = $result->fetch_assoc()) 
	{
		$rating_count[$row["movieid"]]++;
    }

	for($y = 1; $y<=100; $y++)
	{
		echo $rating_count[$y]." ";
	}

	for($x = 1; $x<164890; $x++)
	{	
		$sql1 = "UPDATE moviedata SET no_of_users_rated = '$rating_count[$x]' WHERE movieid = '$x' ";
		//$sql1 = "INSERT INTO moviedata (no_of_users_rated) VALUES ($rating_count[$x]) WHERE movieid = '$x' ";
		if($conn->query($sql1) == TRUE)
			echo $x." "; 

	}
		$conn->close();
?>