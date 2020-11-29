<?php 
include ("includes/header.php");
include("includes/Classes/Message.php"); 
include("includes/Classes/User.php");
include("includes/Classes/Post.php");



$messageObj=new Message($con,$userLoggedIn);
if(isset($_GET['u']))
	$userTo=$_GET['u'];
else{
	$userTo=$messageObj->getMostRecentUser();
	if($userTo==false)
		$userTo='new';
}
if($userTo!="new")
	$userToObj=new User($con,$userTo);
if(isset($_POST['post_message'])){
  if(isset($_POST['message_body'])){
    $body=mysqli_real_escape_string($con,$_POST['message_body']);
    $date=date("Y-m-d H:i:s");
    $messageObj->sendMessage($userTo,$body,$date);
    header("Location:Messages.php?u=$userTo");
  }
}

?>
 <div class="user_details column" id="profile_messages">
  	<a href="<?php echo $userLoggedIn; ?>">
  		
  		<img src="<?php echo $user['profilePic']; ?>">

  	</a>	
  	<div class="user_details_left_right">
  	<a href="<?php echo $userLoggedIn; ?>">
  		<?php 

		echo "<br>".$user['firstName']." ".$user['lastName']."<br>";?>
    </a>
    
		<?php 
    echo "Events :".$user['events']."<br>";	
		echo " Departament :".$user['department'];
  	 ?>
  	
  	</div>
  </div>
  <div class="main_column column" id="main_cloumn">
  	<?php 
  	if($userTo !="new"){
  		echo "<h4>You and <a href='$userTo'>".$userToObj->getFirstAndLastName()."</a></h4><br><br>";
      echo "<div class='loaded_messages' id='scroll_messages'>";
      echo $messageObj->getMessages($userTo);
      echo"</div>";

    }
  	 ?>
  	<div class="message_post">
  		<form action="" method="POST">
  			<?php 
  				if($userTo=="new"){
  					echo"Select the friend you would like to message <br><br>";
         ?>   
  					To: <input type='text' onkeyup='getUsers(this.value,"<?php echo $userLoggedIn;?>")' name='q' placeholder='Name' autocomplete='off' id='search_text_input'>
            <?php
  					 echo"<div class='results'></div>";
  				}
      				else{
      					echo"<textarea name='message_body' id='message_textarea' placeholder='Write your message'> </textarea> ";
      					echo"<input type='submit' name='post_message' class='info' id='message_submit' value='Send'>";
      				}
             ?>
  		</form>
  		
  	</div>
  
  </div>

  <script >
    var div=document.getElementById("scroll_messages");
    div.scrollTop=div.scrollHeight;
  </script>
  <div class="user_details column" id="conversations">
    <h4>Conversations</h4>

    <div class="loaded_conversations">
      <?php echo $messageObj->getConvos(); ?>
      
    </div>
    <br>
    <a href="Messages.php?u=new">New Message</a>
  </div>