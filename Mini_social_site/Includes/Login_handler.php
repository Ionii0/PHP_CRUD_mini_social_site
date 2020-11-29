<?php 
if(isset($_POST['login_button'])){
	$username=$_POST['log_username'];//insert username
	$_SESSION['log_username']=$_POST['log_username'];
	$pw=strip_tags($_POST['log_password']);
	$pw=md5($pw);//insert password

	


	//Check user and pw
	//---------------------------------------------------
	$checkQuery=mysqli_query($con,"SELECT * FROM users WHERE userName='$username' AND password='$pw' ");
	$queryCheck=mysqli_num_rows($checkQuery);
	
	echo $queryCheck;
	echo $_SESSION['log_username'];
	
	if($queryCheck==1){
		$row=mysqli_fetch_array($checkQuery);
		$username=$row['userName'];
		$_SESSION['username']=$username;
		header("Location: EuroAvia.php");
		exit();
	}else{array_push($error,"Username and password are incorrect");}

	//---------------------------------------------------

 }
	



?>