<?php 
	class Post{
		private $userObj;
		private $con;

		public function __construct($con,$user){
			$this->con=$con;
			$this->userObj=new User($con,$user);
		}
		public function submitPost($body,$userTo){
			$body=strip_tags($body);//Remove html tags
			$body=mysqli_real_escape_string($this->con,$body);
			$checkEmpty=preg_replace('/\s+/','', $body);//Deletes all spaces
			if($checkEmpty !=""){
				$dateAdded=date("Y-m-d H:i:s");//Date and time
				//Get username
				$addedBy=$this->userObj->getUsername();
				//If posting on own profile
		
				//Insert post
				$query=mysqli_query($this->con,"INSERT INTO posts VALUES('','$body','$addedBy','$userTo','$dateAdded','no','no','0')");
				$returnedId=mysqli_insert_id($this->con);
				//Insert notification
				




		}



		}

		//-----------------PostsFriends----------------------------------
		public function loadPostsFriends($data,$limit){

			$page=$data['page'];
			$userLoggedIn=$this->userObj->getUsername();

			if($page==1){
				$start=0;
			}
			else{
				$start=($page-1)*$limit;
			}

			
			$str="";//Return this string
			$dataQuery=mysqli_query($this->con,"SELECT * FROM posts WHERE deleted='no' ORDER BY id DESC");

			if(mysqli_num_rows($dataQuery)>0){
				$numIterations=0;//Num of results checked
				$count=1;

			while($row=mysqli_fetch_array($dataQuery)){
				$id=$row['id'];
				$body=$row['body'];
				$addedBy=$row['addedBy'];
				$dateTime=$row['dateAdded'];

				//Prepare userTo string so it can be included even if not posted to a user
				if($row['userTo']=='none'){
				$userTo="";
				}else{
					$userToObj=new User($this->con,$row['userTo']);
					$userToName=$userToObj->getFirstAndLastName();
					$userTo="to <a href=' " .$row['userTo']."'> ".$userToName."</a";
					}

					$userLoggedObj=new User($this->con,$userLoggedIn);
					if($userLoggedObj->isFriend($addedBy)){

						if($numIterations++ < $start)
							continue;
						//Once 10 posts loaded, stop
						if($count>$limit){
							break;
						}else{
							$count++;
						}
						if($userLoggedIn==$addedBy)
							$deleteButton="<button class='delete_button btn-danger' id='post$id'>delete</button>";
						
						else
							$deleteButton="";
						
					//User who posted
					$userDetailsQuery=mysqli_query($this->con,"SELECT firstName , lastName ,profilePic FROM users WHERE userName='$addedBy' ");
					$userRow=mysqli_fetch_array($userDetailsQuery);
					$firstName=$userRow['firstName'];
					$lastName=$userRow['lastName'];
					$profilePic=$userRow['profilePic'];
					?>
					<script>
						function toggle<?php echo $id; ?>(e) {
 
					     if( !e ) e = window.event;
					 
					     var target = $(e.target);
					 
					     if (!target.is("a")) {
					         var element = document.getElementById("toggleComment<?php echo $id; ?>");
					 
					         if(element.style.display == "block") 
					             element.style.display = "none";
					         else 
					             element.style.display = "block";
					     }
 }
					</script>

					<?php
					$commentsCheck=mysqli_query($this->con,"SELECT * FROM comments WHERE postId='$id'");
					$commentsCheckNum=mysqli_num_rows($commentsCheck);


					//Time
					$dateTimeNow=date("Y-m-d H:i:s");
					$startDate=new DateTime($dateTime);//Post time
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


					$str.="<div class='status_post' onClick='javascript:toggle$id(event)'>
								<div class='post_profile_pic'>
									<img src='$profilePic' width='50'
								</div>	
								<div class='posted_by' style='color:#ACACAC;'>
								<a href='$addedBy'>$firstName $lastName </a> $userTo <br>&nbsp;&nbsp;&nbsp;&nbsp;$timeMessage <br>
								$deleteButton
								</div>
								<div id='post_body'>
									$body
									
							
								</div>

							</div>
								<div class='newsfeed_post_options'>
									Comments($commentsCheckNum)&nbsp;&nbsp;&nbsp;&nbsp
									<iframe src='Fly.php?postId=$id' scrolling='no'> </iframe>
								</div>
								</div>

							<div class='post_comment' id='toggleComment$id'style='display:none;'>

							<iframe src='Comment_frame.php?postId=$id' id='comment_iframe' width=100%;></iframe> 
							</div>
							<hr>";
					}
					?>

					<script >
						$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("Includes/Delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});


					});
					</script>

					<?php

			}

			if($count > $limit) 
    $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'><input type='hidden' class='noMorePosts' value='false'>";
else 
    $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align:center;' class='noMorePostsText'> No more posts to show! </p>";
		}	
			echo $str;
 					
		}



		//--------------------------------ProfilePosts------------------------
		public function loadProfilePosts($data,$limit){

			$page=$data['page'];
			$profileUser=$data['profileUsername'];
			$userLoggedIn=$this->userObj->getUsername();

			if($page==1){
				$start=0;
			}
			else{
				$start=($page-1)*$limit;
			}

			
			$str="";//Return this string
			$dataQuery=mysqli_query($this->con,"SELECT * FROM posts WHERE deleted='no' AND ((
				addedBy='$profileUser' AND userTo='none')OR userTo='$profileUser')  ORDER BY id DESC");

			if(mysqli_num_rows($dataQuery)>0){
				$numIterations=0;//Num of results checked
				$count=1;

			while($row=mysqli_fetch_array($dataQuery)){
				$id=$row['id'];
				$body=$row['body'];
				$addedBy=$row['addedBy'];
				$dateTime=$row['dateAdded'];

				//Prepare userTo string so it can be included even if not posted to a user
				if($row['userTo']=='none'){
				$userTo="";
				}else{
					$userToObj=new User($this->con,$row['userTo']);
					$userToName=$userToObj->getFirstAndLastName();
					$userTo="to <a href=' " .$row['userTo']."'> ".$userToName."</a";
					}

					$userLoggedObj=new User($this->con,$userLoggedIn);
					if($userLoggedObj->isFriend($addedBy)){

						if($numIterations++ < $start)
							continue;
						//Once 10 posts loaded, stop
						if($count>$limit){
							break;
						}else{
							$count++;
						}
						if($userLoggedIn==$addedBy)
							$deleteButton="<button class='delete_button btn-danger' id='post$id'>delete</button>";
						else
							$deleteButton="";
					//User who posted
					$userDetailsQuery=mysqli_query($this->con,"SELECT firstName , lastName ,profilePic FROM users WHERE userName='$addedBy' ");
					$userRow=mysqli_fetch_array($userDetailsQuery);
					$firstName=$userRow['firstName'];
					$lastName=$userRow['lastName'];
					$profilePic=$userRow['profilePic'];
					?>
					<script>
						function toggle<?php echo $id; ?>(e) {
 
					     if( !e ) e = window.event;
					 
					     var target = $(e.target);
					 
					     if (!target.is("a")) {
					         var element = document.getElementById("toggleComment<?php echo $id; ?>");
					 
					         if(element.style.display == "block") 
					             element.style.display = "none";
					         else 
					             element.style.display = "block";
					     }
 }
					</script>

					<?php
					$commentsCheck=mysqli_query($this->con,"SELECT * FROM comments WHERE postId='$id'");
					$commentsCheckNum=mysqli_num_rows($commentsCheck);


					//Time
					$dateTimeNow=date("Y-m-d H:i:s");
					$startDate=new DateTime($dateTime);//Post time
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


					$str.="<div class='status_post' onClick='javascript:toggle$id(event)'>
								<div class='post_profile_pic'>
									<img src='$profilePic' width='50'
								</div>	
								<div class='posted_by' style='color:#ACACAC;'>
								<a href='$addedBy'>$firstName $lastName </a> &nbsp;&nbsp;&nbsp;&nbsp;$timeMessage
								$deleteButton
								</div>
								<div id='post_body'>
									$body
									
							
								</div>

							</div>
								<div class='newsfeed_post_options'>
									Comments($commentsCheckNum)&nbsp;&nbsp;&nbsp;&nbsp
									<iframe src='Fly.php?postId=$id' scrolling='no'> </iframe>
								</div>
								</div>

							<div class='post_comment' id='toggleComment$id'style='display:none;'>

							<iframe src='Comment_frame.php?postId=$id' id='comment_iframe' width=100%;></iframe> 
							</div>
							<hr>";
					}
					?>

					<script >
						$(document).ready(function() {

						$('#post<?php echo $id; ?>').on('click', function() {
							bootbox.confirm("Are you sure you want to delete this post?", function(result) {

								$.post("Includes/Delete_post.php?post_id=<?php echo $id; ?>", {result:result});

								if(result)
									location.reload();

							});
						});


					});
					</script>

					<?php

			}

			if($count > $limit) 
    $str .= "<input type='hidden' class='nextPage' value='" . ($page + 1) . "'><input type='hidden' class='noMorePosts' value='false'>";
else 
    $str .= "<input type='hidden' class='noMorePosts' value='true'><p style='text-align:center;' class='noMorePostsText'> No more posts to show! </p>";
		}	
			echo $str;
 					
		}



	}
	
 ?>