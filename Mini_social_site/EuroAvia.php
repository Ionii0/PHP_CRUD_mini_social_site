 <?php 
 	include("Includes/Header.php");
  include("Includes/Classes/User.php");
  include("includes/Classes/Post.php");

if(isset($_POST['post'])){
  $post=new Post($con,$userLoggedIn);
  $post->submitPost($_POST['post_text'],'none');
  header("Location: EuroAvia.php");
}

  ?>


  
<div class="wrapper">  
  <div class="user_details column">
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

  <div class="main_column column">
    <form class="post_form" action="EuroAvia.php" method="POST">
      <textarea name="post_text" id="post_text" placeholder="Got something to say?"></textarea>
      <input type="submit" name="post" id="post_button" value="Post">
      <hr>
    </form>
    
    
     <div class="posts_area">
   <img id="loading" src="Css/Images/loading.gif">
  </div>

  <script>
    $(function(){
 
        var userLoggedIn = '<?php echo $userLoggedIn; ?>';
        var inProgress = false;
 
        loadPosts(); //Load first posts
 
        $(window).scroll(function() {
            var bottomElement = $(".status_post").last();
            var noMorePosts = $('.posts_area').find('.noMorePosts').val();
 
            // isElementInViewport uses getBoundingClientRect(), which requires the HTML DOM object, not the jQuery object. The jQuery equivalent is using [0] as shown below.
            if (isElementInView(bottomElement[0]) && noMorePosts == 'false') {
                loadPosts();
            }
        });
 
        function loadPosts() {
            if(inProgress) { //If it is already in the process of loading some posts, just return
                return;
            }
            
            inProgress = true;
            $('#loading').show();
 
            var page = $('.posts_area').find('.nextPage').val() || 1; //If .nextPage couldn't be found, it must not be on the page yet (it must be the first time loading posts), so use the value '1'
 
            $.ajax({
                url: "Includes/Ajax_load_posts.php",
                type: "POST",
                data: "page=" + page + "&userLoggedIn=" + userLoggedIn,
                cache:false,
 
                success: function(response) {
                    $('.posts_area').find('.nextPage').remove(); //Removes current .nextpage 
                    $('.posts_area').find('.noMorePosts').remove(); //Removes current .nextpage 
                    $('.posts_area').find('.noMorePostsText').remove(); //Removes current .nextpage 
 
                    $('#loading').hide();
                    $(".posts_area").append(response);
 
                    inProgress = false;
                }
            });
        }
 
        //Check if the element is in view
        function isElementInView (el) {
            var rect = el.getBoundingClientRect();
 
            return (
                rect.top >= 0 &&
                rect.left >= 0 &&
                rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) && //* or $(window).height()
                rect.right <= (window.innerWidth || document.documentElement.clientWidth) //* or $(window).width()
            );
        }
    });
 
    </script>


</div>


</div>
</body>
</html>