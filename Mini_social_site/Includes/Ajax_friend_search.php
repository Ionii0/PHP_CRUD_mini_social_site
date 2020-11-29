<?php 
	include("../config/config.php");
	include("classes/user.php");
	$query=$_POST['query'];
	$userLoggedIn=$_POST['userLoggedIn'];
	$names=explode(" ",$query);

	if(strpos($query,"_")!==false){
		$usersReturned=mysqli_query($con,"SELECT * FROM users WHERE userName LIKE '$query%' LIMIT 8 ");
	}
	else if(count($names)==2){
		$usersReturned=mysqli_query($con,"SELECT * FROM users WHERE (firstName LIKE '%$names[0]%' AND lastName LIKE '%$names[1]%') LIMIT 8");
	}
	else{
		$usersReturned=mysqli_query($con,"SELECT * FROM users WHERE (firstName LIKE '%$names[0]%' OR lastName LIKE '%$names[0]%') LIMIT 8");
	}
	if($query!=""){
		while($row=mysqli_fetch_array($usersReturned)){
			$user=new User($con,$userLoggedIn);
			if($row['userName']!=$userLoggedIn){
				$mutualFriends=$user->getMutualFriends($row['userName'])."friends in common";
			}
		
			else{
				$mutualFriends="";
			}
			if($user->isFriend($row['userName'])){
				echo "<div class='result_display' >
					 	<a href='messages.php?u=".$row['userName']."' style='color:#000'> 
					 		<div class='live_search_profile_pic'>
					 		<img src='".$row['profilePic']."'>
					 		</div>
					 		<div class='live_search_text'>".$row['firstName']." ".$row['lastName']."<p style='margin:0px;'>".$row['userName']."</p>
					 			<p id='grey' >".$mutualFriends."</p>
					 		</div>
					 		</a>
					 	</div>";
			}
		}
	}
 ?>