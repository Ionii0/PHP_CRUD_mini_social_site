<?php
	require 'Config/Config.php';
	require 'Includes/Register_handler.php';
	require 'Includes/Login_handler.php';
?>


<!DOCTYPE html>
<html>
<div class="wrapper">
<head>
	<title>Welcome to EuroAvia-Bucuresti</title>
	<link rel="stylesheet" type="text/css" href="Css/Register_style.css">
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
	<script src="Js/Register_js.js"></script>

</head>
<body>
<?php //Showing register page after pressing register page
if(isset($_POST['reg_button'])){
	echo '
	<script>
	$(document).ready(function(){
		$("#first").hide();
		$("#second").show();	
		});
	</script>
	';
}
?>
	
	<div class="login_box">
		<h1>Welcome!</h1>
		
			<div id="first">
					<h2>Login</h2>
					<form action="Register.php" method="POST">
						<input type="text" name="log_username" placeholder="username">
						<br>
						<input type="password" name="log_password" placeholder="password">
						<br>
						<input type="submit" name="login_button" value="Login">
						<br>
						
							<?php if(in_array("Username and password are incorrect",$error)){
								echo "Username and password are incorrect";} ?>
							
								
						<br>
						<a href="#" id="signup" class="signup">Need an account? Register here !</a>

					</form>
			</div>

			<div id="second">		

					<h2>Register</h2>
					<form function="Register.php" method="POST"> 
						<input type="text" name="reg_fname" placeholder="First Name" value="<?php if(isset($_SESSION['reg_fname'])) 
						echo $_SESSION['reg_fname'] ?>" required>
						
						
						<?php if(in_array("First name and last name can contain at least 2 letters and maximim 25 letters",$error)){

							echo "<br>First name and last name can contain at least 2 letters and maximim 25 letters";} ?>
						<br>
						<input type="text" name="reg_lname" placeholder="Last Name" 
						value="<?php if(isset($_SESSION['reg_lname'])) 
						echo $_SESSION['reg_lname'] ?>" required>
						
						<br>
						<input type="text" name="reg_username" placeholder="Username" value="<?php if(isset($_SESSION['reg_username'])) 
						echo $_SESSION['reg_username'] ?>" required>
						
						<?php if(in_array("The username must contain at least 2 letters , maximum 20",$error)){
							echo "<br>The username must contain at least 2 letters , maximum 20";} 
							if(in_array("This usernamer has been already taken",$error)){
							echo "<br>This usernamer has been already taken";} ?>
						<br>
						<input type="email" name="reg_email" placeholder="E-mail" value="<?php if(isset($_SESSION['reg_email'])) 
						echo $_SESSION['reg_email'] ?>"  required>
						
						<?php if(in_array("This email has been used already ",$error))
							echo "<br>This email has been used already ";
							if(in_array("Invalid format",$error))
							echo "<br>Invalid format";
							if(in_array("E-mails don't match",$error))
							echo "<br>E-mails don't match"; ?>
						<br>
						<input type="email" name="reg_email2" placeholder="E-mail confirm" value="<?php if(isset($_SESSION['reg_email2'])) 
						echo $_SESSION['reg_email2'] ?>"  required>
						
						<br>
						<input type="password" name="reg_password" placeholder="Password" required>
						
						<?php if(in_array("The passwords don't match each other",$error))
								echo "<br>The passwords don't match each other";
							  if(in_array("The password must contain at least 6 letters , maximum 20",$error))
							  	echo "<br>The password must contain at least 6 letters , maximum 20";?>
						<br>
						<input type="password" name="reg_password2" placeholder="Password confirm" required>
						
						<br>
						<input type="submit" name="reg_button" value="Register">
								
						<?php if(isset($_POST['reg_button']))
						if(empty($error))
						echo "You have succesfully registered" ?>
							
					<a href="#" id="signin" class="sigin">Already have an account?Sign in here !</a>

					</form>
			
			</div>		
		</div>

	</div>				
</body>
</html>