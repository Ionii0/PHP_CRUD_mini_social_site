<?php 
	include("includes/Header.php");
	include("Includes/Classes/User.php");
  	include("includes/Classes/Post.php");	
 ?>
 <div class="main_column2 " id="main_column">
 	<h4>Friend Requests</h4>
 	<?php 
 		$query=mysqli_query($con,"SELECT * FROM friendRequests WHERE userTo='$userLoggedIn' ");
 		if(mysqli_num_rows($query)==0){
 			echo "You have no friend requests for now !";}
 			else{
 				while($row=mysqli_fetch_array($query)){
	 					$userFrom=$row['userFrom'];
	 					$userFromObj=new User($con,$userFrom);
	 					
	 					echo $userFromObj->getFirstAndLastName()." sent you a friend request!"."<br>";
	 					$userFromFriendArray=$userFromObj->getFriendArray();
	 					if(isset($_POST['accept_request'.$userFrom])){
	 						$addFriendQuery=mysqli_query($con,"UPDATE users SET friendArray=CONCAT(friendArray, '$userFrom,') WHERE userName='$userLoggedIn'");
	 						$addFriendQuery2=mysqli_query($con,"UPDATE users SET friendArray=CONCAT(friendArray, '$userLoggedIn,') WHERE userName='$userFrom'");
	 						$deleteQuery=mysqli_query($con,"DELETE FROM friendRequests WHERE userTo='$userLoggedIn'AND userFrom='$userFrom' ");
	 						echo "You are now friends !";
	 						header("Location: Requests.php");
	 					}
	 					if(isset($_POST['ignore_request'.$userFrom])){
	 						$deleteQuery=mysqli_query($con,"DELETE FROM friendRequests WHERE userTo='$userLoggedIn'AND userFrom='$userFrom' ");
	 						echo "You have ignored the request !";
	 						header("Location: Requests.php");
 					}
 					?>
 					<form action="Requests.php" method="POST">
 	 	<input type="submit" name="accept_request<?php echo $userFrom; ?>" id="accept_button" value="Accept">
 	 	<input type="submit" name="ignore_request<?php echo $userFrom; ?>" id="ignore_button" value="Ignore">
 	 	
 	 </form>
 			<?php	
 				}
 			}

 		
 	 ?>	
</div>