<!-- ---------- only 'Administrator' can use this page ----------------> 
<?php if($_SESSION['role'] != 'Administrator'):?>
	<h2>Sorry! You are not authorized to use this page.</h2>
	<a href="../index.php" class="btn btn-primary add-del-btn">Home</a>
	<a href="posts.php?source=" class="btn btn-primary add-del-btn">View Posts</a>
	<a href="profile.php" class="btn btn-primary add-del-btn">Profile</a>
	<a href="../includes/logout.php" class="btn btn-primary add-del-btn">Log Out</a>
<?php else:?>

<?php
	// -------------------- if 'Add User' is submitted -------------------
	if(isset($_POST['addusersubmit'])) {
		// get all input data
		$user_uname				= mysqli_real_escape_string($con, $_POST['user_uname']);
		$user_email				= mysqli_real_escape_string($con, $_POST['user_email']);
		$user_email_val		= filter_var($user_email, FILTER_VALIDATE_EMAIL);
		$user_pass1				= mysqli_real_escape_string($con, $_POST['user_pass1']);
		$user_pass2				= mysqli_real_escape_string($con, $_POST['user_pass2']);
		$user_fname				= mysqli_real_escape_string($con, $_POST['user_fname']);
		$user_lname				= mysqli_real_escape_string($con, $_POST['user_lname']);
		$user_image				= $_FILES['user_image']['name'];
		
		if($user_image == "") {
			$user_image = 'default.png';
		}		
		
		$image_tmp = $_FILES['user_image']['tmp_name'];
		$user_role = $_POST['user_role'];
		$user_status = $_POST['user_status'];
		
		// check if username is already in use in both cms_users and cms_comments
		$q = "SELECT cms_users.user_uname FROM cms_users 
					WHERE user_uname = '$user_uname'
					UNION 
					SELECT cms_comments.comment_author FROM cms_comments 
					WHERE comment_author = '$user_uname'";
	
		$r = mysqli_query($con, $q);
		
		if(empty($user_uname) || empty($user_email) || empty($user_pass1) || empty($user_pass2)) {
			$div_class = 'danger';
			$div_msg = 'Please fill in all required fields.';
		} elseif($user_pass1 !== $user_pass2) {
			$div_class = 'danger';
			$div_msg = 'Password fields do not match. Please try again.';
		} elseif(!$user_email_val) {
			$div_class = 'danger';
			$div_msg = 'Please enter a valid email address.';
		} elseif(mysqli_num_rows($r) > 0) {
			$div_class = 'danger';
			$div_msg = 'Sorry, that username is already in use. Please choose another.';
		} else { 
			// encrypt password (see documentation on php.net)		
			$options =['cost'=>HASHCOST];
			$user_pass = password_hash($user_pass1, PASSWORD_BCRYPT, $options);	
					
			move_uploaded_file($image_tmp, "../images/$user_image");
			
			$q = "INSERT INTO cms_users
					(user_uname, user_pass, user_fname, user_lname, user_email,
					user_image, user_role, user_status, user_date)
					VALUES ('$user_uname', '$user_pass', '$user_fname', '$user_lname',
					'$user_email', '$user_image', '$user_role', '$user_status', now())";
			
			$result = mysqli_query($con, $q);
			
			$div_info = confirmQuery($result, 'insert');
			$div_class 		= $div_info['div_class'];
			$div_msg 			= $div_info['div_msg'];
			
			// reset to a blank form
			$user_uname = '';
			$user_email = '';
			$user_pass1 = '';
			$user_pass2 = '';
			$user_fname = '';
			$user_lname = '';
		}
	// start with a blank form
	} else {
		$user_uname = '';
		$user_email = '';
		$user_pass1 = '';
		$user_pass2 = '';
		$user_fname = '';
		$user_lname = '';
	}
?>

<?php
	// if 'Clear Form' button is pressed
	if(isset($_POST['clearform'])) {
		$user_uname = '';
		$user_email = '';
		$user_pass1 = '';
		$user_pass2 = '';
		$user_fname = '';
		$user_lname = '';
	}
?>

<h2 class="page-title">Add User:</h2>
<!---------------------------- alert div --------------------------- -->
<?php if(!empty($div_msg)):?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-<?php echo $div_class;?>">
			<?php echo $div_msg;?>
		</div>
	</div>
</div>
<?php endif;?>

<form action="" method="post" enctype="multipart/form-data">

	<div class="row">	<!-- ------------- 1st row --------------------- -->
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_uname">Username *</label>
			<input type="text" class="form-control" name="user_uname" 
				value="<?php echo $user_uname;?>">
		</div>	
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_email">Email Address *</label>
			<input type="text" class="form-control" name="user_email"
				value="<?php echo $user_email;?>">
		</div>
	</div>
	</div>
	
	<div class="row">	<!-- ------------- 2nd row --------------------- -->
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_pass1">Password *</label>
			<input type="password" class="form-control" name="user_pass1">
		</div>	
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_pass2">Retype Password *</label>
			<input type="password" class="form-control" name="user_pass2">
		</div>
	</div>
	</div>
	
	<div class="row">	<!-- ------------- 3rd row --------------------- -->
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_fname">First Name</label>
			<input type="text" class="form-control" name="user_fname"
				value="<?php echo $user_fname;?>">
		</div>	
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_lname">Last Name</label>
			<input type="text" class="form-control" name="user_lname"
				value="<?php echo $user_lname;?>">
		</div>
	</div>
	</div>
	
	<div class="row">	<!-- ------------- 4th row --------------------- -->
	<div class="col-md-6">	
		<div class="form-group">
			<label for="user_image">User Image</label>
			<input type="file" accept="image/*" name="user_image">
		</div>
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_role">Role</label>
			
			<select name="user_role">
			<?php
				// loop thru each role to display select option; show any existing role as selected
				$roles = array('Subscriber','Administrator','Tourist');
			?>
			<?php	foreach($roles as $role):?>
			<?php 		if($role == $user_role):?>
				<option value="<?php echo $role;?>" selected><?php echo $role;?></option>
			<?php 		else:?>
				<option value="<?php echo $role;?>"><?php echo $role;?></option>
			<?php		endif;?>
			<?php	endforeach;?>	
			</select>
		</div>	
		<div class="form-group">
			<label for="user_status">Status</label>
			
			<select name="user_status">
			<?php
				// loop thru each status to display select option; show any existing status as selected
				$statuses = array('Active','Inactive','Banned');
			?>
			<?php	foreach($statuses as $status):?>
			<?php 		if($status == $user_status):?>
				<option value="<?php echo $status;?>" selected><?php echo $status;?></option>
			<?php 		else:?>
				<option value="<?php echo $status;?>"><?php echo $status;?></option>
			<?php		endif;?>
			<?php	endforeach;?>
			</select>
		</div>	
	</div>
	</div>
	<button type="submit" name="addusersubmit" class="btn btn-success add-del-btn">
			<i class="fa fa-plus"></i> Add User</button>
	<a href="users.php" class="btn btn-primary">
			<i class="fa fa-eye"></i> View All Users</a>
	<button type="submit" name="clearform" class="btn btn-default add-del-btn">
			<i class="fa fa-eraser"></i> Clear Form</button>
</form>
<?php endif;?>		<!-- only 'Administrator' can use this page -->