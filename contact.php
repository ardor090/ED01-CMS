<?php  include "includes/database.php"; ?>
<?php  include "includes/header.php"; ?>
<?php  include "includes/navigation_reg.php"; ?>

<?php
// Code copied from W3Schools.com
// define variables and set to empty values
$div_class = $div_msg = "";
$email = $sub = $msg = "";

if (isset($_POST["submit"])) {
	if (empty($_POST["email"])) {
		$div_class = 'danger';	
    	$div_msg .= "Email is required. ";
	} else {
		$email = test_input($_POST["email"]);
		// check if e-mail address is well-formed
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
			$div_class = 'danger';	
			$div_msg .= "Invalid email format. "; 
		}
	}
	
	if (empty($_POST["sub"])) {
		$div_class = 'danger';	
		$div_msg .= "Subject is required.";
	} else {
		$sub = test_input($_POST["sub"]);
		// check if name only contains letters and whitespace
		if (!preg_match("/^[a-zA-Z ]*$/", $sub)) {
			$div_class = 'danger';	
			$div_msg .= "Only letters and white space allowed in Subject."; 
		}
	}
     
	if (empty($_POST["msg"])) {
		$msg = "";
		} else {
		$msg = wordwrap(test_input($_POST["msg"]), 60);
	}
	
	$header = "From: " . $email;
	
	// if $div_class has not been set to 'danger', then validations
	// pass and ok to send mail
	
	if($div_class == "") {
		if(mail('admin@saharadb.com', $sub, $msg, $header)) {
			$div_class = 'success';
			$div_msg = 'Your message was successfully sent.';
			$email = $sub = $msg = "";		
		}	else {
			$div_class = 'danger';
			$div_msg = 'There was an error sending your email.';
		}
	}
}

function test_input($data) {
   $data = trim($data);
   $data = stripslashes($data);
   $data = htmlspecialchars($data);
   return $data;
}
?>
<!-- Page Content -->
<div class="container">

	<section id="login">
		<div class="container">
			<div class="row">
				<div class="col-md-6 col-md-offset-3">
					<div class="form-wrap">
						<h1 id="reg-h1">Contact Page</h1>
						<form role="form" action="contact.php" method="post" 
							id="login-form" autocomplete="off">
							
							<?php if(!empty($div_msg)):?>							
							<div class="alert alert-<?php echo $div_class;?>">
								<?php echo $div_msg;?>
							</div>
							<?php endif;?>
											
							<div class="form-group">
								<label for="email">Your Email Address</label>
								<input type="email" name="email" class="form-control" 
									value="<?php echo $email;?>">
							</div>
							<div class="form-group">
								<label for="sub">Subject</label>
								<input type="text" name="sub" class="form-control" 
									value="<?php echo $sub;?>">
							</div>
							<div class="form-group">
								<label for="msg">Message</label>
								<textarea name="msg" class="form-control" rows="10"><?php echo $msg;?></textarea>
							</div>
							
							<input type="submit" name="submit" id="btn-login" 
								class="btn btn-custom btn-lg btn-block" value="Send">
						</form>
						
					</div>
				</div> <!-- /.col-md-12 -->
			</div> <!-- /.row -->
		</div> <!-- /.container -->
	</section>
<hr>

<?php include "includes/footer.php";?>
