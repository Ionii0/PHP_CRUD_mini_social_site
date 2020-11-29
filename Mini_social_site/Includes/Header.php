<?php 
//include("Includes/Classes/User.php");
//include("Includes/Classes/Post.php");
//include("Includes/Classes/Message.php");
if(is_readable('Config/Config.php')){
require 'Config/Config.php';}
	else{
		ob_start();
session_start();
$timezone=date_default_timezone_set("Europe/Bucharest");

	$con=mysqli_connect("localhost","root","","euroavia-s");//Conn
		if(mysqli_connect_error()){
			echo mysqli_connect_error();}
	}



if(isset($_SESSION['log_username'])){
	$userLoggedIn=$_SESSION['log_username'];
	$userDetailsQuery=mysqli_query($con,"SELECT * FROM users WHERE userName='$userLoggedIn'");
	$user=mysqli_fetch_array($userDetailsQuery);
}else{header("Location: Register.php");}
?>

<!DOCTYPE html>
<html>
<head>
	<title></title>

	<!--Javascript-->
	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="Js/bootstrap.js"></script>
	<script src="Js/Euroavia.js"></script>
	<script src="Js/bootbox.min.js"></script>




	<!--Css-->
	<link rel="stylesheet" type="text/css" href="Css/bootstrap.css">
	<link rel="stylesheet" type="text/css" href="Css/Header.css">
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.1/css/all.css" integrity="sha384-50oBUHEmvpQ+1lW4y57PTFmhCaXp0ML5d60M1M7uH2+nqUivzIebhndOJK28anvf" crossorigin="anonymous">

</head>
<body>
	<div class="top_bar">
		<div class="logo">
			<a href="EuroAvia.php">EuroAvia-Bucuresti</a>
		</div>
		<!---------------Navigation bar------->
	<nav>
		<?php 
		//$messages=new Message($con,$userLoggedIn);
		//$numMessages=$messages->getUnreadNumber();

		 ?>


		<a href="<?php echo $userLoggedIn; ?>">
				<?php echo $user['firstName']; ?>	
		</a>

		<a href="EuroAvia.php">
			<i class="fa fa-home fa-lg"></i>
		</a>
		<a href="javascript:void(0);"onclick="getDropdownData('<?php echo $userLoggedIn;?>','message')"> 
			<i class="fa fa-envelope fa-lg"></i>
			<?php  
				/*if($numMessages>0){
				echo "<span class='notification_badge' id='unread_message'>".$numMessages."</span>
				</a>";}*/
		?>
			
		<a href="#">
			<i class="fa fa-bell fa-lg"></i>
		</a>
		<a href="Requests.php">
			<i class="fa fa-users fa-lg"></i>
		</a>
		<a href="#">
			<i class="fa fa-cog fa-lg"></i>
		</a>
		
		


	</nav>	
	<div class="dropdown_data_window" style="height:0px; border:none;margin-top:38px;position:absolute;right:10px;background-color: #446cb3;border-radius:0px 0px 8px 8px;"></div>
	<input type="hidden" id="dropdown_data_type" value="">	


	</div>		

	