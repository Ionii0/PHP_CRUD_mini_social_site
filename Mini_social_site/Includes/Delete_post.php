<?php 
	require '../Config/Config.php';
	if(isset($_GET['post_id'])){
		$postId=$_GET['post_id'];
	}
	if(isset($_POST['result'])){
		if($_POST['result']=='true')
			$query=mysqli_query($con,"UPDATE posts SET deleted='yes' WHERE id='$postId'");
	}
 ?>