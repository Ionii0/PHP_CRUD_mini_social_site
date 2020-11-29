<!DOCTYPE html>
<?php 
		require 'Config/Config.php';
		include("Includes/Classes/User.php");
		include("Includes/Classes/Post.php");
			if(isset($_SESSION['log_username'])){
				$userLoggedIn=$_SESSION['log_username'];
				$userDetailsQuery=mysqli_query($con,"SELECT * FROM users WHERE userName='$userLoggedIn'");
				$user=mysqli_fetch_array($userDetailsQuery);
			}else{header("Location: Register.php");}

		
	 ?>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="Css/Header.css">
</head>
<body>

	<style type="text/css">
		*{
			font-family: Arial,Helvetica,sans-serif;
		}
		body{
			background-color: #fff;
		}
		form{
			top:21px;
			position: absolute;
		}
	</style>




	<?php 
	if(isset($_GET['postId'])){
			$postId=$_GET['postId'];
		}
		$getFlies=mysqli_query($con,"SELECT addedBy , flies FROM posts WHERE id='$postId'");
		$row=mysqli_fetch_array($getFlies);
		$totalFlies=$row['flies'];
		$userFlying=$row['addedBy'];
		$userDetailsQuery=mysqli_query($con,"SELECT * FROM users WHERE userName='$userFlying'");
		$row=mysqli_fetch_array($userDetailsQuery);
		$totalUserFlies=$row['events'];

		//Like button
		if(isset($_POST['fly_button'])){
			$totalFlies++;
			$totalUserFlies++;
			$query=mysqli_query($con,"UPDATE posts SET flies='$totalFlies' WHERE id='$postId'");
			$userFlies=mysqli_query($con,"UPDATE users SET events='$totalUserFlies' WHERE userName='$userFlying'");
			$insertUser=mysqli_query($con,"INSERT INTO fly VALUES('','$userLoggedIn','$postId') ");
				//Insert not
		}

		//Unlike button
		if(isset($_POST['unfly_button'])){
			$totalFlies--;
			$totalUserFlies--;
			$query=mysqli_query($con,"UPDATE posts SET flies='$totalFlies' WHERE id='$postId'");
			$userFlies=mysqli_query($con,"UPDATE users SET events='$totalUserFlies' WHERE userName='$userFlying'");
			$insertUser=mysqli_query($con,"DELETE FROM fly WHERE userName='$userLoggedIn' AND postId='$postId' ");
				
		}


		//Check for previous likes
		$checkQuery=mysqli_query($con,"SELECT * FROM fly WHERE userName='$userLoggedIn' AND postId='$postId'");
		$numRows=mysqli_num_rows($checkQuery);
		if($numRows>0){
			echo '<form action="Fly.php?postId='.$postId.'" method="POST" >
					<input type="submit" class="comment_fly" name="unfly_button" value="Unfly">
					<div class="fly_value">
						('.$totalFlies.' Flying)
					</div>
				</form>';
		}
		else{
			echo '<form action="Fly.php?postId='.$postId.'" method="POST" >
					<input type="submit" class="comment_fly" name="fly_button" value="Fly">
					<div class="fly_value">
						('.$totalFlies.' Flying)
					</div>
				</form>';
		}
	 ?>

</body>
</html>