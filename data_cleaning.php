<?php
	$conn = new mysqli("localhost","root","recommender","majorproject");

	$sql = "SELECT * FROM moviedata";
	$result = $conn->query($sql); 
	$row = mysqli_fetch_array($result);
	$search_term = ", The";

	if (strpos($row["moviename"], $search_term) !== false) {
    				//echo $row["moviename"].'<br>';
					$temp = $row["moviename"];
					$temp = "The ".str_replace($search_term,"",$temp);
    				$x = $row["movieid"];
    				$sql = "UPDATE moviedata SET moviename = '$temp' WHERE movieid = '$x'";
    				$conn->query($sql);
				}
			while($row = $result->fetch_assoc()) {

				if (strpos($row["moviename"], $search_term) !== false) {
    				//echo $row["moviename"].'<br>';
    				$temp = $row["moviename"];
					echo $temp;
					echo '<br>';
					$temp = "The ".str_replace($search_term,"",$temp);
					echo $temp;
					echo '<br>';
					$x = $row["movieid"];
					$temp = str_replace("'","''",$temp);
    				$sql1 = "UPDATE moviedata SET moviename = '$temp' WHERE movieid = '$x'";
    				$conn->query($sql1);
				}

    		}

$conn->close();

?>
