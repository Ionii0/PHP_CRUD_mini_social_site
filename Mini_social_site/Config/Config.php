<?php 
ob_start();
session_start();
$timezone=date_default_timezone_set("Europe/Bucharest");

	$con=mysqli_connect("localhost","root","","euroavia-s");//Conn
		if(mysqli_connect_error()){
			echo mysqli_connect_error();
	}





?>