<?php 
	class User{
		private $user;
		private $con;

		public function __construct($con,$user){
			$this->con=$con;
			$userDetailsQuery=mysqli_query($this->con,"SELECT * FROM users WHERE userName='$user'");
			$this->user=mysqli_fetch_array($userDetailsQuery);
		}
		public function getFirstAndLastName(){
			$username=$this->user['userName'];
			$query=mysqli_query($this->con,"SELECT firstName , lastName FROM users WHERE userName='$username'");
			$row=mysqli_fetch_array($query);
			return $row['firstName']." ".$row['lastName'];
		}

		public function getUsername(){
			return $this->user['userName'];
		}

		public function isFriend($usernameToCheck){
			$addComma=",".$usernameToCheck.",";
			if((strstr($this->user['friendArray'],$addComma)||$usernameToCheck==$this->user['userName'])){
				return true;
			}else{return false;}

		}

		public function getProfilePic(){
			$username=$this->user['userName'];
			$query=mysqli_query($this->con,"SELECT profilePic FROM users WHERE userName='$username'");
			$row=mysqli_fetch_array($query);
			return $row['profilePic'];
		}
		public function getFriendArray(){
			$username=$this->user['userName'];
			$query=mysqli_query($this->con,"SELECT friendArray FROM users WHERE userName='$username'");
			$row=mysqli_fetch_array($query);
			return $row['friendArray'];
		}
		public function didReciveRequest($userFrom){
			$userTo=$this->user['userName'];
			$checkRequestQuery=mysqli_query($this->con,"SELECT * FROM friendrequests WHERE userTo='$userTo' AND userFrom='$userFrom'");
			if(mysqli_num_rows($checkRequestQuery)>0){
				return true;
			}
			else {
				return false;
			}

		}
		public function didSendRequest($userTo){
			$userFrom=$this->user['userName'];
			$checkRequestQuery=mysqli_query($this->con,"SELECT * FROM friendrequests WHERE userTo='$userTo' AND userFrom='$userFrom'");
			if(mysqli_num_rows($checkRequestQuery)>0){
				return true;
			}else{return false;}

		}

		public function removeFriend($userToRemove){
			
			$loggedInUser=$this->user['userName'];
			$query=mysqli_query($this->con,"SELECT friendArray FROM users WHERE userName='$userToRemove'");
			$row=mysqli_fetch_array($query);
			$friendArrayUsername=$row['friendArray'];

			//Remove from logged in user
			$newFriendArray=str_replace($userToRemove.",","",$this->user['friendArray']);
			$removeFriend=mysqli_query($this->con,"UPDATE users SET friendArray='$newFriendArray'WHERE userName='$loggedInUser'");
			//Remove from the no longer friend
			$newFriendArray=str_replace($this->user['userName'].",","",$friendArrayUsername);
			$removeFriend=mysqli_query($this->con,"UPDATE users SET friendArray='$newFriendArray'WHERE userName='$userToRemove'");

			
		}
		public function sendRequest($userTo){
			$userFrom=$this->user['userName'];
			$query=mysqli_query($this->con,"INSERT INTO friendrequests VALUES ('','$userTo','$userFrom')");			
		}

		public function getMutualFriends($userToCheck){
			$mutualFriends=0;
			$userArray=$this->user['friendArray'];
			$userArrayExplode=explode(',',$userArray);

			$query=mysqli_query($this->con,"SELECT friendArray FROM users WHERE userName='$userToCheck' ");
			$row=mysqli_fetch_array($query);
			$userToCheckArray=$row['friendArray'];
			$userToCheckArrayExplode=explode(",",$userToCheckArray);
			foreach ($userArrayExplode as $i) {
				foreach ($userToCheckArrayExplode as $j) {
					if($i==$j && $i!=""){
						$mutualFriends++;
					}
				}
				
			}
			return $mutualFriends;
		}
	
	}

 ?>