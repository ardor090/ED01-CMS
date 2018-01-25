<?php  include "includes/database.php"; ?>
<?php  include "includes/header.php"; ?>
<?php  include "includes/navigation_reg.php"; ?>

<?php
if(isset($_POST['submit'])) {
	// clean up inputs
	$username 		= mysqli_real_escape_string($con, $_POST['username']);
	$email 			= mysqli_real_escape_string($con, $_POST['email']);
	$email_val		= filter_var($email, FILTER_VALIDATE_EMAIL);
	$pass1 			= mysqli_real_escape_string($con, $_POST['password1']);
	$pass2 			= mysqli_real_escape_string($con, $_POST['password2']);
	$fname				= mysqli_real_escape_string($con, $_POST['fname']);
	$lname				= mysqli_real_escape_string($con, $_POST['lname']);
	
	// check if username is already in use in both cms_users and cms_comments
	$q = "SELECT cms_users.user_uname FROM cms_users 
				WHERE user_uname = '$username'
				UNION 
				SELECT cms_comments.comment_author FROM cms_comments 
				WHERE comment_author = '$username'";
	
	$r = mysqli_query($con, $q);
	
	// check if all fields are entered, then check if passwords are the same
	if(empty($username) || empty($email) || empty($pass1) || empty($pass2)
		|| empty($fname) || empty($lname)) {
		$div_class = 'danger';
		$div_msg = 'Please fill in all required fields.';
	} elseif($pass1 !== $pass2) {
		$div_class = 'danger';		
		$div_msg = 'Passwords fields do not match. Please try again.';
	}	elseif(!$email_val) {
		$div_class = 'danger'; 
		$div_msg = 'Please enter a vaild email address.';
	} elseif(mysqli_num_rows($r) > 0) {
		$div_class = 'danger';
		$div_msg = 'Sorry, that username is already in use. Please choose another.';
	} else {
		$options =['cost'=>HASHCOST];
		$pass = password_hash($pass1, PASSWORD_BCRYPT, $options);	
			
		$q = "INSERT INTO cms_users
					(user_uname, user_pass, user_fname, user_lname, user_email)
					VALUES ('$username', '$pass', '$fname', '$lname', '$email')";
	
		$reg = mysqli_query($con, $q);
			
		if($reg) {
			$div_class = 'success';
			$div_msg = 'You Have Successfully Registered! ';
			$div_msg .= 'Go to <a href="index.php">Home Page</a> to log in.';
			$username = "";
			$email = "";
			$fname = "";
			$lname = "";
		} else {
			$div_class = 'danger';
			$div_msg = 'Database error: '.mysqli_error($con);			
		}
	}
} else {
	// initialize default field values
	$username = "";
	$email = "";
	$fname = "";
	$lname = "";
}
?>


 
<!-- Page Content -->
<div class="container">

	<section id="login">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="form-wrap">
						<h1 id="reg-h1">Registration Page</h1>
						<form role="form" action="registration.php" method="post" 
							id="login-form" autocomplete="off">
							
							<?php if(!empty($div_msg)):?>							
							<div class="alert alert-<?php echo $div_class;?>">
								<?php echo $div_msg;?>
							</div>
							<?php endif;?>
							
							<div class="form-group">
								<label for="username" class="sr-only">Choose a Username</label>
								<input type="text" name="username" class="form-control" 
									value="<?php echo $username;?>" placeholder="Enter Desired Username *">
							</div>
							<div class="form-group">
								<label for="email" class="sr-only">Email Address</label>
								<input type="email" name="email" class="form-control" 
									value="<?php echo $email;?>" placeholder="e.g. somebody@example.com *">
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label for="fname" class="sr-only">First Name</label>
										<input type="text" name="fname" class="form-control" 
											value="<?php echo $fname;?>" placeholder="Your Fist Name *">
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label for="lname" class="sr-only">Last Name</label>
										<input type="text" name="lname" class="form-control" 
											value="<?php echo $lname;?>" placeholder="Your Last Name *">
									</div>
								</div>							
							</div>
							<div class="form-group">
								<label for="password" class="sr-only">Password</label>
								<input type="password" name="password1" id="key1" class="form-control" 
									placeholder="Enter Password *">
							</div>
							<div class="form-group">
								<label for="password" class="sr-only">Re-Type Password</label>
								<input type="password" name="password2" id="key2" class="form-control" 
									placeholder="Re-Type Password *">
							</div>

							<input type="submit" name="submit" id="btn-login" class="btn btn-custom btn-lg btn-block" value="Register">
						</form>
						
 
					</div>
				</div> <!-- /.col-md-12 -->
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</section>
<hr>

<?php include "includes/footer.php";?>
