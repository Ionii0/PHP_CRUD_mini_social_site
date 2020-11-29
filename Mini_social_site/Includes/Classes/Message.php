<?php 
class Message{
	private $userObj;
	private $con;
	public function __construct($con,$user){
		$this->con=$con;
		$this->userObj=new User($con,$user);
	}
	public function getMostRecentUser(){
		$userLoggedIn=$this->userObj->getUsername();
		$query=mysqli_query($this->con,"SELECT userTo , userFrom FROM messages WHERE userTo='$userLoggedIn' OR userFrom='$userLoggedIn' ORDER BY  id DESC LIMIT 1");
		if(mysqli_num_rows($query)==0)
			return false;
		$row=mysqli_fetch_array($query);
		$userTo=$row['userTo'];
		$userFrom=$row['userFrom'];
		if($userTo!=$userLoggedIn){
			return $userTo;
		}
		else{
			return $userFrom;
		}
	}
	public function sendMessage($userTo,$body,$date){
		if($body!=""){
			$userLoggedIn=$this->userObj->getUsername();
			$query=mysqli_query($this->con,"INSERT INTO messages VALUES('','$userTo','$userLoggedIn','$body','$date','no','no','no')");

		}
	}
	public function getMessages($otherUser){
		$userLoggedIn=$this->userObj->getUsername();
		$data="";
		$query=mysqli_query($this->con,"UPDATE messages SET opened='yes' WHERE userTo='$userLoggedIn' AND userFrom='$otherUser'");
		$getMessagesQuery=mysqli_query($this->con,"SELECT*FROM messages WHERE (userTo='$userLoggedIn' AND userFrom='$otherUser')OR (userTo='$otherUser' AND userFrom='$userLoggedIn')");
		while($row=mysqli_fetch_array($getMessagesQuery)){
			$userTo=$row['userTo'];
			$userFrom=$row['userFrom'];
			$body=$row['body'];

			$divTop=($userTo==$userLoggedIn) ? "<div class='message'id='green'>":"<div class='message' id='blue'>";
			$data=$data.$divTop.$body."</div><br><br>";
		}
		return $data;
	}
	public function getLatestMessage($userLoggedIn,$user2){
		$detailsArray=array();
		$query=mysqli_query($this->con,"SELECT body,userTo,date FROM messages WHERE (userTo='$userLoggedIn' AND userFrom='$user2')OR(userTo='$user2' AND userFrom='$userLoggedIn') ORDER BY id DESC LIMIT 1");
		$row=mysqli_fetch_array($query);
		$sentBy=($row['userTo']==$userLoggedIn) ? "They said :" : "You said: ";
		//Time
		$dateTimeNow=date("Y-m-d H:i:s");
		$startDate=new DateTime($row['date']);//Post time
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

		array_push($detailsArray,$sentBy);
		array_push($detailsArray,$row['body']);
		array_push($detailsArray,$timeMessage);

		return $detailsArray;
	}
	public function getConvos(){
		$userLoggedIn=$this->userObj->getUsername();
		$returnString="";
		$convos=array();
		$query=mysqli_query($this->con,"SELECT userTo , userFrom FROM messages WHERE userTo='$userLoggedIn'OR userFrom='$userLoggedIn' ORDER BY id DESC ");
		while($row=mysqli_fetch_array($query)){
			$userToPush=($row['userTo']!=$userLoggedIn) ? $row['userTo'] : $row['userFrom'];
			if(!in_array($userToPush,$convos)){
				array_push($convos,$userToPush);
			}
		}
		foreach($convos as $userName){
			$userFoundObj=new User($this->con,$userName); 
			$latestMessageDetails=$this->getLatestMessage($userLoggedIn,$userName);
			$dots=(strlen($latestMessageDetails[1])>=12) ? "..." : "";
			$split=str_split($latestMessageDetails[1],12);
			$split=$split[0].$dots;

			$returnString.="<a href='messages.php?u=$userName'> <div class='user_found_messages'> <img src='".$userFoundObj->getProfilePic()."' style='border-radius:5px;margin-right:5px;'>".$userFoundObj->getFirstAndLastName()."<span class='timestamp_smaller' id='grey'>".$latestMessageDetails[2]."</span> <p id='grey' style='margin:0';> ".$latestMessageDetails[0].$split."</p>
				</div>
				</a>";
		}
		return $returnString;
	}
	public function getConvosDropdown($data,$limit){

		$page=$data['page'];
		$userLoggedIn=$this->userObj->getUsername();
		$returnString="";
		$convos=array();

		if($page==1){
			$start=0;
		}else{
			$start=($page-1)*$limit;
		}

		$setViewedQuery=mysqli_query($this->con,"UPDATE messages SET viewed='yes' WHERE userTo='$userLoggedIn'");	

		$query=mysqli_query($this->con,"SELECT userTo , userFrom FROM messages WHERE userTo='$userLoggedIn'OR userFrom='$userLoggedIn' ORDER BY id DESC ");
		while($row=mysqli_fetch_array($query)){
			$userToPush=($row['userTo']!=$userLoggedIn) ? $row['userTo'] : $row['userFrom'];
			if(!in_array($userToPush,$convos)){
				array_push($convos,$userToPush);
			}
		}


		$numIterations=0;
		$count=1;

		foreach($convos as $userName){

			if($numIterations++ <$start)
				continue;

			if($count>$limit)
				break;
			else
				$count++;
			$isUnreadQuery=mysqli_query($this->con,"SELECT opened FROM messages WHERE userTo='$userLoggedIn'AND userFrom='$userName'ORDER BY id DESC");
			$row=mysqli_fetch_array($isUnreadQuery);
			$style=($row['opened']=='no')?"background-color:#DDEDFF;":"";

			$userFoundObj=new User($this->con,$userName); 
			$latestMessageDetails=$this->getLatestMessage($userLoggedIn,$userName);
			$dots=(strlen($latestMessageDetails[1])>=12) ? "..." : "";
			$split=str_split($latestMessageDetails[1],12);
			$split=$split[0].$dots;

			$returnString.="<a href='messages.php?u=$userName'> <div class='user_found_messages' style='".$style."'> <img src='".$userFoundObj->getProfilePic()."' style='border-radius:5px;margin-right:5px;'>".$userFoundObj->getFirstAndLastName()."<span class='timestamp_smaller' id='grey'>".$latestMessageDetails[2]."</span> <p id='grey' style='margin:0';> ".$latestMessageDetails[0].$split."</p>
				</div>
				</a>";
		}
		if($count>$limit)
			$returnString.="<input type='hidden'class='nextPageDropdownData' value='".($page+1)."'><input type='hidden' class='noMoreDropdownData' value='false'>";
		else
			$returnString.="<input type='hidden' class='noMoreDropdownData' value='true'><p style='text-align:center;'>No more messages to load</p>";

		return $returnString;
	}

	public function getUnreadNumber(){
		$userLoggedIn=$this->userObj->getUsername();
		$query=mysqli_query($this->con,"SELECT * FROM messages WHERE viewed='no' AND userTo='$userLoggedIn'");
		return mysqli_num_rows($query);
	}
}
 ?>
 