<?php
	$conn = new mysqli("localhost","root","recommender","majorproject");

	$sql = "SELECT * FROM moviedata";
	$result = $conn->query($sql); 
	$row = mysqli_fetch_array($result);
	$search_term = ', The';

	if (strpos(strtolower($row["moviename"]), strtolower($search_term)) !== false) {
    				//echo $row["moviename"].'<br>';
					$temp = $row["moviename"];
					$temp = 'The '.str_replace($search_term,"",$temp);
    				$x = $row["movieid"];
    				$sql = "UPDATE moviedata SET moviename = '$temp' WHERE movieid = '$x'";
    				$conn->query($sql);
				}
			while($row = $result->fetch_assoc()) {

				if (strpos(strtolower($row["moviename"]), strtolower($search_term)) !== false) {
    				//echo $row["moviename"].'<br>';
    				$temp = $row["moviename"];
					$temp = 'The '.str_replace($search_term,"",$temp);
					$x = $row["movieid"];
    				$sql = "UPDATE moviedata SET moviename = '$temp' WHERE movieid = '$x'";
    				$conn->query($sql);
				}

    		}

$conn->close();

?>