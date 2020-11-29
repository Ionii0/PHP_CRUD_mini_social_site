<?php 
//Variables taken out of form
	//-------------------------------
	$fname="";//firstName
	$lname="";//lastName
	$username="";//userName
	$em="";//mail
	$em2="";//mail confirm
	$pw="";//password
	$pw2="";//password confirm
	$date="";//signDate
	$error=array();//Error array
	if(isset($_POST['reg_button'])){
		//Input variables
		//-------------------------
		
		//First name
		$fname=strip_tags($_POST['reg_fname']);//Resolve html tags
		$fname=str_replace(' ','-',$fname);//Remove unwanted space
		$fname=ucfirst($fname);//First letter uppercase
		$_SESSION['reg_fname']=$fname;

		//Last name
		$lname=strip_tags($_POST['reg_lname']);//Resolve html tags
		$lname=str_replace(' ','-',$lname);//Remove unwanted space
		$lname=ucfirst($lname);//First letter uppercase
		$_SESSION['reg_lname']=$lname;

		//Username
		$username=strip_tags($_POST['reg_username']);//Resolve html tags
		$username=str_replace(' ','',$username);//Remove unwanted space
		$_SESSION['reg_username']=$username;
	
		//email
		$em=strip_tags($_POST['reg_email']);//Resolve html tags
		$em=str_replace(' ','',$em);//Remove unwanted space
		$em=ucfirst($em);//First letter uppercase
		$_SESSION['reg_email']=$em;

		//email2
		$em2=strip_tags($_POST['reg_email2']);//Resolve html tags
		$em2=str_replace(' ','',$em2);//Remove unwanted space
		$em2=ucfirst($em2);//First letter uppercase
		$_SESSION['reg_email2']=$em2;

		//password
		$pw=strip_tags($_POST['reg_password']);//Resolve html tags
		
		
		//password2
		$pw2=strip_tags($_POST['reg_password2']);//Resolve html tags
	

		//Date
		$date=date("Y-m-d");//Reg date



		//------------------------------------------------------------
		//Mail confirmation
		if($em==$em2){
			if(filter_var($em,FILTER_VALIDATE_EMAIL)){
				$em=filter_var($em,FILTER_VALIDATE_EMAIL);

				//Check if mail exists
				$emCheck=mysqli_query($con,"SELECT mail FROM users WHERE mail='$em' ");
				$emNoumber=mysqli_num_rows($emCheck);
				if($emNoumber>0){
					array_push($error,"This email has been used already ");
					
				}

			}
			else{array_push($error,"Invalid format");}
		}
		else{array_push($error,"E-mails don't match");}

		//----------------------------------------------------------
		//Password check
		if($pw!=$pw2){
			array_push($error,"The passwords don't match each other");
		}
		if(strlen($pw)>20||strlen($pw)<6){
			array_push($error,"The password must contain at least 6 letters , maximum 20");
		}
		//----------------------------------------------------------
		//Username check
		if(preg_match('[^A-za-z0-9]',$username)){
			array_push($error,"The username can contain only letters and noumbers");
		}
		if(strlen($username)>20||strlen($username)<2){
			array_push($error,"The username must contain at least 2 letters , maximum 20");
		}
		$usernameCheck=mysqli_query($con,"SELECT mail FROM users WHERE username='$username'" );
		$usernameNoumber=mysqli_num_rows($usernameCheck);
		if($usernameNoumber>0){
			array_push($error,"This usernamer has been already taken");
			
		}
		//---------------------------------------------------------
		//Lname and fname
		if(strlen($fname)>25||strlen($lname)>25||strlen($fname)<2||strlen($lname)<2){
			array_push($error,"First name and last name can contain at least 2 letters and maximim 25 letters");
		}
		//--------------------------------------------------------
		//Introducing variables in db
		if(empty($error)){
			$pw=md5($pw);//Encrypt password
			$profilePic="Css/Images/sigla.png";//User picture
			$insert=mysqli_query($con,"INSERT INTO users VALUES('','$fname','$lname','$username','$em','$pw','$date','$profilePic','0','none','')");


			//Clearin session variables
			$_SESSION['reg_fname']="";
			$_SESSION['reg_lname']="";
			$_SESSION['reg_username']="";
			$_SESSION['reg_email']="";
			$_SESSION['reg_email2']="";

		}
		//-------------------------------------------------------

		
		
	

	//Last acolada-------down-------	
	}
 ?>