<?php
	/*
	Method 1: genre based recommendations
	Method 2: collaborative filtering
	*/
	session_start();
	
	if(empty($_SESSION['userid'])){
		header('location:login.php');
	}
	$conn = new mysqli("localhost","root","recommender","majorproject");
	
	$userid = $_SESSION['userid'];

	$url = $_SERVER['REQUEST_URI'];
		



	if($url[strlen($url)-1]=='1'){

		$sql1 = "SELECT movieid FROM ratings WHERE userid='$userid' ";
		$result1 = $conn->query($sql1); 
		$row1 = mysqli_fetch_array($result1);

		$genres = array();
		$movies = array();

		$b = $row1["movieid"];

		array_push($movies,$row1["movieid"]);



		$sql2 = "SELECT genre FROM moviedata WHERE movieid='$b' ";
		$result2 = $conn->query($sql2); 
		$row2 = mysqli_fetch_array($result2);
		$c = $row2["genre"];
		$temp = "";

		for($x = 0; $x < strlen($c); $x++){
			if($c[$x]=='|'){

				if(array_key_exists($temp, $genres)){
					$genres[$temp]++;
				}

				else{
					$genres[$temp]=1;
				}
				$temp = "";
			}

			else{
				$temp = $temp.$c[$x];
			}
		}


		if(strlen($temp)){
			if(array_key_exists($temp, $genres)){
					$genres[$temp]++;
				}

				else{
					$genres[$temp]=1;
				}
				$temp = "";
		}

		while($row1 = $result1->fetch_assoc()) {
			$b = $row1["movieid"];

			$sql2 = "SELECT genre FROM moviedata WHERE movieid='$b' ";
			$result2 = $conn->query($sql2); 
			$row2 = mysqli_fetch_array($result2);
			$c = $row2["genre"];
			$temp = "";

			array_push($movies,$row1["movieid"]);

			for($x = 0; $x < strlen($c); $x++){
				if($c[$x]=='|'){

					if(array_key_exists($temp, $genres)){
						$genres[$temp]++;
					}

					else{
						$genres[$temp]=1;
					}
					$temp = "";
				}

				else{
					$temp = $temp.$c[$x];
				}
			}

			if(strlen($temp)){
				if(array_key_exists($temp, $genres)){
						$genres[$temp]++;
					}

					else{
						$genres[$temp]=1;
					}
					$temp = "";
			}
		}

		arsort($genres);


		foreach ($genres as $key => $value) {
		    echo "Genre: $key"."<br>";
			
			$a = 0;
			$aname = "";
			
			$b = 0;
			$bname = "";
			
			$c = 0;
			$cname = "";

		$sql3 = "SELECT movieid, moviename, genre, avg_rating FROM moviedata WHERE no_of_users_rated > 10";
		$result3 = $conn->query($sql3); 
		$row3 = mysqli_fetch_array($result3);
		
			if (strpos($row3["genre"],$key) !== false && in_array($row3["movieid"], $movies) === false){
					if($row3["avg_rating"] > $a){
						$c = $b;
						$cname = $bname;
						$b = $a;
						$bname = $aname;
						$a = $row3["avg_rating"];
						$aname = $row3["moviename"];
					}

					else if($row3["avg_rating"] > $b){
						$c = $b;
						$cname = $bname;
						$b = $row3["avg_rating"];
						$bname = $row3["moviename"];				
					}

					else if($row3["avg_rating"] > $c){
						$c = $row3["avg_rating"];
						$cname = $row3["moviename"];										
					}
			}

			while($row3 = $result3->fetch_assoc()){

				if (strpos($row3["genre"],$key) !== false &&in_array($row3["movieid"], $movies) === false){

					if($row3["avg_rating"] > $a){
						$c = $b;
						$cname = $bname;
						$b = $a;
						$bname = $aname;
						$a = $row3["avg_rating"];
						$aname = $row3["moviename"];
					}

					else if($row3["avg_rating"] > $b){
						$c = $b;
						$cname = $bname;
						$b = $row3["avg_rating"];
						$bname = $row3["moviename"];				
					}

					else if($row3["avg_rating"] > $c){
						$c = $row3["avg_rating"];
						$cname = $row3["moviename"];										
					}
				}
			}

			//echo $key."<br>";
			//echo $value."<br>";
			echo $aname." ".$a."<br>";
			echo $bname." ".$b."<br>";
			echo $cname." ".$c."<br>";
			echo "<br>";
		}		
	

	}

/*-------------------------------------------------------------------------------------------------*/

	else if($url[strlen($url)-1]=='2'){
		$start = microtime(true);

		$sql1 = "SELECT movieid,rating FROM ratings WHERE userid='$userid' ";
		$result1 = $conn->query($sql1); 

		$movies = array();
		$ratings = array();

		while($row1=mysqli_fetch_assoc($result1)){
			array_push($movies,$row1["movieid"]);
			array_push($ratings,$row1["rating"]);
		}

		$max_similarity = -1;
		$max_similarity_user = -1;


		for($comp_user = 1; $comp_user<=671; $comp_user++){

			if($comp_user!=$userid){
		
				$sql2 = "SELECT movieid,rating FROM ratings WHERE userid='$comp_user' ";
				$result2 = $conn->query($sql2);

				$marked = array_fill(0,count($movies),0);
				$score = 0;
				while($row2=mysqli_fetch_assoc($result2)){
					
					if(in_array($row2["movieid"], $movies)){

						$index = -1;
						for($x = 0; $x < count($movies); $x++){

							if($movies[$x]==$row2["movieid"]){
								$index = $x;
								break;
							}
						}

						$score = $score + ($row2["rating"]-$ratings[$index])*($row2["rating"]-$ratings[$index]);
						
						//$score = $score + abs($row2["rating"]-$ratings[$index]);

						$marked[$index] = 1;
					}

					else{
						$score = $score + ($row2["rating"])*($row2["rating"]);
						//$score = $score + ($row2["rating"]);
					}
				}

				for($y = 0; $y < count($movies); $y++){
					if($marked[$y]==0){
						$score = $score + ($ratings[$y])*($ratings[$y]);
						//$score = $score + ($ratings[$y]);
					}
				}

				$score = 1/(sqrt($score) + 1);

				if($score > $max_similarity){
					$max_similarity = $score;
					$max_similarity_user = $comp_user;
				}
			}
			

		}

		echo $max_similarity."<br>";
		echo $max_similarity_user."<br>";

		$sql3 = "SELECT movieid FROM ratings WHERE userid='$max_similarity_user' ";
		$result3 = $conn->query($sql3);

		$max_similarity_user_array = array();
		while($row3=mysqli_fetch_assoc($result3)){
			if(in_array($row3["movieid"], $movies) === false){
				array_push($max_similarity_user_array, $row3["movieid"]);	
			}
		}

		for($x = 0; $x < count($max_similarity_user_array); $x++){
			
			$sql4="SELECT moviename,avg_rating FROM moviedata WHERE movieid='$max_similarity_user_array[$x]'";
			$result4 = $conn->query($sql4);
			while($row4=mysqli_fetch_assoc($result4)){
				echo $row4["moviename"]." ".$row4["avg_rating"]."<br>";
			}			

		}


		$time_elapsed_secs = microtime(true) - $start;
		echo $time_elapsed_secs;
	}


/*-------------------------------------------------------------------------------------------------*/


	else if($url[strlen($url)-1]=='3'){

		$start = microtime(true);


		//echo "3";
		$sql1 = "SELECT movieid,rating FROM ratings WHERE userid='$userid' ";
		$result1 = $conn->query($sql1); 

		$movies = array();
		


		while($row1=mysqli_fetch_assoc($result1)){
			$movies[$row1["movieid"]] = $row1["rating"];
		}

		$max_similarity = -1;
		$max_similarity_user = -1;

		for($comp_user = 1; $comp_user<=671; $comp_user++){
			
			if($comp_user!=$userid){
				$sql2 = "SELECT movieid,rating FROM ratings WHERE userid='$comp_user' ";
				$result2 = $conn->query($sql2);

				$marked = array();
				$score = 0;
				while($row2=mysqli_fetch_assoc($result2)){
					
					if(array_key_exists($row2["movieid"],$movies)){
						//$score = $score + ($row2["rating"]-$movies[$row2["movieid"]])*($row2["rating"]-$movies[$row2["movieid"]]);
						
						$score = $score + ($row2["rating"]-$movies[$row2["movieid"]]);

						$marked[$row2["movieid"]] = 1;
					}

					else{
						//$score = $score + ($row2["rating"])*($row2["rating"]);
						$score = $score + ($row2["rating"]);
					}
				}

				/*
				foreach ($genres as $key => $value) {
			    echo "Genre: $key"."<br>";
			    */

				foreach ($movies as $key => $value){
					if(array_key_exists($key,$marked)==false){
						//$score = $score + ($value)*($value);
						$score = $score + ($value);
					}
				}

				//$score = 1/(sqrt($score) + 1);
				$score = 1/($score + 1);

				if($score > $max_similarity){
					$max_similarity = $score;
					$max_similarity_user = $comp_user;
				}

			}

		}

		echo $max_similarity."<br>";
		echo $max_similarity_user."<br>";

		$sql3 = "SELECT movieid FROM ratings WHERE userid='$max_similarity_user' ";
		$result3 = $conn->query($sql3);

		$max_similarity_user_array = array();
		while($row3=mysqli_fetch_assoc($result3)){
			if(array_key_exists($row3["movieid"],$movies)==false){
				array_push($max_similarity_user_array, $row3["movieid"]);
			}
		}

		for($x = 0; $x < count($max_similarity_user_array); $x++){
			
			$sql4="SELECT moviename,avg_rating FROM moviedata WHERE movieid='$max_similarity_user_array[$x]'";
			$result4 = $conn->query($sql4);
			while($row4=mysqli_fetch_assoc($result4)){
				echo $row4["moviename"]." ".$row4["avg_rating"]."<br>";
			}			

		}


		$time_elapsed_secs = microtime(true) - $start;
		echo $time_elapsed_secs;

	}


/*-------------------------------------------------------------------------------------------------*/


	else if($url[strlen($url)-1]=='4'){
		$start = microtime(true);

		//echo "3";
		$sql1 = "SELECT movieid,rating FROM ratings WHERE userid='$userid' ";
		$result1 = $conn->query($sql1); 

		$movies = array();

		while($row1=mysqli_fetch_assoc($result1)){
			$movies[$row1["movieid"]] = $row1["rating"];
		}


		$relevant_users = array();
		for($x = 0; $x <= 671; $x++){
			$relevant_users[$x]=0;
		}

		foreach ($movies as $key => $value) {
			$sql4 = "SELECT userid FROM ratings WHERE movieid='$key' ";
			$result4 = $conn->query($sql4); 
			while($row4=mysqli_fetch_assoc($result4)){
				$relevant_users[$row4["userid"]]=1;
			}
		}

		$max_similarity = -1;
		$max_similarity_user = -1;

		for($t = 1; $t<=671; $t++){
			if($relevant_users[$t]==1 && $t!=$userid){
				$sql2 = "SELECT movieid,rating FROM ratings WHERE userid='$t' ";
				$result2 = $conn->query($sql2);

				$marked = array();
				$score = 0;
				while($row2=mysqli_fetch_assoc($result2)){
					
					if(array_key_exists($row2["movieid"],$movies)){
						//$score = $score + ($row2["rating"]-$movies[$row2["movieid"]])*($row2["rating"]-$movies[$row2["movieid"]]);
						
						$score = $score + ($row2["rating"]-$movies[$row2["movieid"]]);

						$marked[$row2["movieid"]] = 1;
					}

					else{
						//$score = $score + ($row2["rating"])*($row2["rating"]);
						$score = $score + ($row2["rating"]);
					}
				}

				/*
				foreach ($genres as $key => $value) {
			    echo "Genre: $key"."<br>";
			    */

				foreach ($movies as $key => $value){
					if(array_key_exists($key,$marked)==false){
						//$score = $score + ($value)*($value);
						$score = $score + ($value);
					}
				}

				//$score = 1/(sqrt($score) + 1);
				$score = 1/($score + 1);

				if($score > $max_similarity){
					$max_similarity = $score;
					$max_similarity_user = $t;
				}

			}

		}

		echo $max_similarity."<br>";
		echo $max_similarity_user."<br>";

		$sql3 = "SELECT movieid FROM ratings WHERE userid='$max_similarity_user' ";
		$result3 = $conn->query($sql3);

		$max_similarity_user_array = array();
		while($row3=mysqli_fetch_assoc($result3)){
			if(array_key_exists($row3["movieid"],$movies)==false){
				array_push($max_similarity_user_array, $row3["movieid"]);
			}
		}

		for($x = 0; $x < count($max_similarity_user_array); $x++){
			
			$sql4="SELECT moviename,avg_rating FROM moviedata WHERE movieid='$max_similarity_user_array[$x]'";
			$result4 = $conn->query($sql4);
			while($row4=mysqli_fetch_assoc($result4)){
				echo $row4["moviename"]." ".$row4["avg_rating"]."<br>";
			}			

		}


		$time_elapsed_secs = microtime(true) - $start;
		echo $time_elapsed_secs;
	}

/*-------------------------------------------------------------------------------------------------*/

?>

