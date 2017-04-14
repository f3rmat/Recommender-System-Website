<?php
	$conn = new mysqli("localhost", "root", "harshit25", "majorproject");

	$sql = "SELECT movieid, rating FROM ratings";
	
	$result = $conn->query($sql); 
	$row = mysqli_fetch_array($result);

	$rating_sum = array();
	$rating_sum = array_fill(0,164980, 0);

	$rating_sum[$row["movieid"]]+=$row["rating"];

	while($row = $result->fetch_assoc()) 
	{
		$rating_sum[$row["movieid"]]+=$row["rating"];
    }


    for($x = 1; $x<164890; $x++)
	{	
		$temp = $rating_sum[$x];
		$sql2 = "SELECT no_of_users_rated FROM moviedata WHERE movieid = '$x'";
		$result = $conn->query($sql2); 
		$row = mysqli_fetch_array($result);
		if($row['no_of_users_rated']!=0 && $conn->query($sql2)==TRUE)
		{
			$temp = 1.0*$temp/$row['no_of_users_rated'];

			$sql3 = "UPDATE moviedata SET avg_rating = '$temp' WHERE movieid = '$x' ";
			//$sql1 = "INSERT INTO moviedata (no_of_users_rated) VALUES ($rating_count[$x]) WHERE movieid = '$x' ";
			if($conn->query($sql3) == TRUE)
				echo $x." ";
		} 

	}
		$conn->close();

?>
