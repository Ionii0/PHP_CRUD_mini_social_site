<?php 
include("includes/Classes/Message.php"); 
	include("Includes/Header.php");
 	include("Includes/Classes/User.php");
  include("includes/Classes/Post.php");
   $messageObj=new Message($con,$userLoggedIn);
 	if(isset($_GET['profile_username'])){
 		$username=$_GET['profile_username'];
 		$userDetailsQuery=mysqli_query($con,"SELECT * FROM users WHERE userName='$username'");
 		$userArray=mysqli_fetch_array($userDetailsQuery);
 		$numFriends=(substr_count($userArray['friendArray'],","))-1;
 	}
  if(isset($_POST['remove_friend'])){
    $user=new User($con,$userLoggedIn);
    $user->removeFriend($username);
  }
  if(isset($_POST['add_friend'])){
    $user=new User($con,$userLoggedIn);
    $user->sendRequest($username);
  }
  if(isset($_POST['respond_request'])){
    header("Location: Requests.php");
  }

  if(isset($_POST['post_message'])){
    if(isset($_POST['message_body'])){
      $body=mysqli_escape_string($con,$_POST['message_body']);
      $date=date("Y-m-d H:i:s");
      $messageObj->sendMessage($username,$body,$date);
    }
    $link='profile_tabs a[href="#messages_div"]';
    echo "<script> 
            $(function (){
              $('".$link."').tab('show');
              });
          </script>";
  }

  ?>


  <!---------PROFILE AND LEFT SIDE BUTTONS  --->

  <div class="profile_left">
  	<img src="<?php echo $userArray['profilePic']; ?>" width='125'>
  	<div class="profile_info" >
  		<p><?php echo "Name :".$userArray['firstName']." ".$userArray['lastName']; ?></p>
  		<p><?php echo "Department:".$userArray['department']; ?></p>
  		<p><?php echo "Events : ".$userArray['events']; ?></p>
  		<p><?php echo "Friends :".$numFriends; ?></p>
  	</div>
  	<form action="<?php echo $username; ?>" method="POST">
  		<?php 
          $profileUserObj=new User($con,$username);
  			  $loggedInUserObj=new User($con,$userLoggedIn);
  			  if($userLoggedIn != $username){
  			  	
  			  	if($loggedInUserObj->isFriend($username)){
  			  		echo '<input type="submit" name="remove_friend" class="danger" value="Remove Friend"<br>';
  			  	}
  			  	else if($loggedInUserObj->didReciveRequest($username)){
  			  		echo '<input type="submit" name="respond_request" class="warning" value="Respond to Request"<br>';
  			  	}	
  			  	else if($loggedInUserObj->didSendRequest($username)){
  			  		echo '<input type="submit" name="respond_request" class="default" value="Respond to Request"<br>';
  			  	}
  			  	else{echo '<input type="submit" name="add_friend" class="success" value=Add Friend><br>';}	
  			  }
          	?>
  	</form>
    <input type="submit"  class="deep_blue" data-toggle="modal" data-target="#post_form" value="POST SOMETHING"> 

<?php 
  if($userLoggedIn!=$username){
    echo '<div class="profile_info_bottom">';
    echo "Mutual friends: ".$loggedInUserObj->getMutualFriends($username);
    echo '</div>';
  }

 ?>

  </div>

    <!--TEXT POSTS MESSAGES -->

  <div class="main_column2">
     <ul class="nav nav-tabs" role="tablist" id="profile_tabs">
      <li role="presentation" class="active"><a href="#newsfeed_div" aria-controls="newsfeed_div" role="tab" data-toggle="tab" class="nav-link">Newsfeed</a></li>
      <li role="presentation"><a href="#messages_div" aria-controls="messages_div" role="tab" data-toggle="tab"class="nav-link">Messages</a></li>
    </ul>

    <div class="tab-content">

      <div role="tabpanel" class="tab-pane fade in active" id="newsfeed_div">
        <div class="posts_area"></div>
        <img id="loading" src="Css/Images/loading.gif">
      </div>


      <div role="tabpanel" class="tab-pane fade" id="messages_div">
        <?php  
        

          echo "<h4>You and <a href='" . $username ."'>" . $profileUserObj->getFirstAndLastName() . "</a></h4><hr><br>";

          echo "<div class='loaded_messages' id='scroll_messages'>";
            echo $messageObj->getMessages($username);
          echo "</div>";
        ?>



        <div class="message_post">
          <form action="" method="POST">
              <textarea name='message_body' id='message_textarea' placeholder='Write your message ...'></textarea>
              <input type='submit' name='post_message' class='info' id='message_submit' value='Send'>
          </form>

        </div>

        <script>
          var div = document.getElementById("scroll_messages");
          div.scrollTop = div.scrollHeight;
        </script>
      </div>


  </div>

<!-- Modal -->
<div class="modal fade" id="post_form" tabindex="-1" role="dialog" aria-labelledby="postModalLabel" aria-hidden="true">
  <div class="modal-dialog" >
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">POST SOMETHING</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <p>This will appear on the user's profile page and also in their newsfeed and your friends's newsfeed !</p>

        <form class="profile_post" action="" method="POST">
           <div class="form-group">
           <textarea class="form-control" name="post_body"></textarea>    
           <input type="hidden" name="user_from" value="<?php echo $userLoggedIn; ?>">
           <input type="hidden" name="user_to" value="<?php echo $username; ?>">
           </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" name="post_button" id="submit_profile_post">POST</button>
      </div>
    </div>
  </div>
</div>


<script>
  var userLoggedIn = '<?php echo $userLoggedIn; ?>';
  var profileUsername = '<?php echo $username; ?>';

  $(document).ready(function() {

    $('#loading').show();

    //Original ajax request for loading first posts 
    $.ajax({
      url: "includes/ajax_load_profile_posts.php",
      type: "POST",
      data: "page=1&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
      cache:false,

      success: function(data) {
        $('#loading').hide();
        $('.posts_area').html(data);
      }
    });

    $(window).scroll(function() {
      var height = $('.posts_area').height(); //Div containing posts
      var scroll_top = $(this).scrollTop();
      var page = $('.posts_area').find('.nextPage').val();
      var noMorePosts = $('.posts_area').find('.noMorePosts').val();

      if ((document.body.scrollHeight == document.body.scrollTop + window.innerHeight) && noMorePosts == 'false') {
        $('#loading').show();

        var ajaxReq = $.ajax({
          url: "includes/ajax_load_profile_posts.php",
          type: "POST",
          data: "page=" + page + "&userLoggedIn=" + userLoggedIn + "&profileUsername=" + profileUsername,
          cache:false,

          success: function(response) {
            $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
            $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 

            $('#loading').hide();
            $('.posts_area').append(response);
          }
        });

      } //End if 

      return false;

    }); //End (window).scroll(function())


  });

  </script>




</div>
</body>
</html>