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
			font-size:12px;
			font-family: Arial,Helvetica,Sans-serif;
		}

	</style>

 	
	 <script>
	 	function toggle(){
	 		var element=document.getElementById("commentSection");
	 		if(element.style.display=="block")
	 			element.style.display="none";
	 		else
	 			element.style.display="block";
	 	}
	 </script>
	 <?php 
	 	if(isset($_GET['postId'])){
	 		$postId=$_GET['postId'];
	 	}

	 	$userQuery=mysqli_query($con,"SELECT addedBy,userTo FROM posts WHERE id='postId'");
	 	$row=mysqli_fetch_array($userQuery);
	 	$postedTo=$row['addedBy'];


	 	if(isset($_POST['postComment'.$postId])){
	 		$postBody=$_POST['post_body'];
	 		$postBody=mysqli_escape_string($con,$postBody);
	 		$dateTimeNow=date("Y-m-d H:i:s");
	 		$insertPost=mysqli_query($con,"INSERT INTO comments VALUES('','$postBody','$userLoggedIn','$postedTo','$dateTimeNow','no',$postId ) ");
	 		echo "<p>Comment Posted !</p>";
	 	}

	  ?>

<form action="Comment_frame.php?postId=<?php echo $postId; ?>" id="comment_form" method="POST" name="postComment<?php echo $postId; ?>">
	<textarea name="post_body"></textarea>
	<input type="submit" name="postComment<?php echo $postId; ?>"value="Post">
	
</form>

	<?php 
		$getComments=mysqli_query($con,"SELECT * FROM comments WHERE postId='$postId' ORDER BY id ASC");
		$count=mysqli_num_rows($getComments);
		if($count!=0){
			while($comment=mysqli_fetch_array($getComments)){
				$commentBody=$comment['postBody'];
				$postedTo=$comment['postedTo'];
				$postedBy=$comment['postedBy'];
				$dateAdded=$comment['dateAdded'];
				$removed=$comment['removed'];


				//Time
					$dateTimeNow=date("Y-m-d H:i:s");
					$startDate=new DateTime($dateAdded);//Post time
					$endDate=new DateTime($dateTimeNow);//Now time
					$interval=$startDate->diff($endDate);//Diff 
					if($interval->y>=1){
						$timeMessage=$interval->y." year(s) ago";
					}else if($interval->m>=1){
						if($interval->d==0){$days="ago";}
						else if($interval->d==1){$days=$interval->d." days ago";}
						else{$days=$interval->d." days ago";}
						

						if($interval->m==1){
							$timeMessage=$interval->m." month".$days;
						}
						else{$timeMessage=$interval->m." months".$days;}
					}
					else if($interval->d>=1){
						if($interval->d==1){
							$timeMessage="Yesterday";
						}
						else{$timeMessage=$interval->d."days ago";}
					}
					else if($interval->h>=1){
						if($interval->h==1){
							$timeMessage=$interval->h."hour ago";
						}
						else{$timeMessage=$interval->h."hours ago";}
					}
					else if($interval->i>=1){
						$timeMessage=$interval->i."minutes ago";
					}
					else if($interval->i<1){
						$timeMessage="Just now";
					}

					$userObj=new User($con,$postedBy);
					?>
						 	<div class="comment_section">
						 		<a href="<?php echo $postedBy; ?>" target="_parent">
						 			<img src="<?php echo $userObj->getProfilePic(); ?> "title="<?php echo $postedBy; ?>" style="float:left;"height=30 >
						 			<?php echo $userObj->getFirstAndLastName(); ?>
						 		</a>
						&nbsp;&nbsp;&nbsp;&nbsp;<?php echo $timeMessage."<br>".$commentBody; ?>
						 		<hr>
	 						</div>	

	 	<?php


			}
		}
		else{
			echo "<center>No comments to show</center>";
		}
	 ?>


</body>
</html>