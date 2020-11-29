<?php 
include("../Config/Config.php");
include("Classes/User.php");
include("Classes/Post.php");


$limit=5;//Number of posts when load
$posts=new Post($con,$_REQUEST['userLoggedIn']);
$posts->loadPostsFriends($_REQUEST,$limit);

 ?>