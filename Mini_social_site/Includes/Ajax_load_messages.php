<?php 
include("../config/config.php");
include("Classes/User.php");
include("Classes/Message.php");

$userLoggedIn=$_SESSION['log_username'];
$limit=7;//Number of messages
$message=new Message($con,$userLoggedIn);
echo $message->getConvosDropdown($_REQUEST,$limit);

 ?>