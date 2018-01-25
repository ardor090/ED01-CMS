<?php
	// ----------------if 'Update User' button is pressed ----------------
	if(isset($_POST['updateusersubmit'])) {
		// get all input data
		$user_id = $_POST['user_id'];
		$user_uname = $_POST['user_uname'];
		
		$user_email				= mysqli_real_escape_string($con, $_POST['user_email']);
		$user_email_val		= filter_var($user_email, FILTER_VALIDATE_EMAIL);		
		$user_pass1				= mysqli_real_escape_string($con, $_POST['user_pass1']);
		$user_pass2				= mysqli_real_escape_string($con, $_POST['user_pass2']);
		$user_fname				= mysqli_real_escape_string($con, $_POST['user_fname']);
		$user_lname				= mysqli_real_escape_string($con, $_POST['user_lname']);
		$user_role					= $_POST['user_role'];
		$user_status				= $_POST['user_status'];
		
		// set the image name to the result of the radio button input		
		switch($_POST['user_image']) {
			case "none":
				$user_image = "default.png";
				break;
			case "new":
				$user_image = $_FILES['new_image']['name'];
				break;
			default:
				$user_image = $_POST['user_image'];		
		}
		
		$image_tmp = $_FILES['new_image']['tmp_name'];
		move_uploaded_file($image_tmp, "../images/$user_image");
		
		// validate then update
			
		if(empty($user_uname) || empty($user_email) || empty($user_pass1) || empty($user_pass2)) {
			$div_class = 'danger';
			$div_msg = 'Please fill in all required fields.';
		} elseif($user_pass1 !== $user_pass2) {
			$div_class = 'danger';
			$div_msg = 'Password fields do not match.';
		} elseif(!$user_email_val) {
			$div_class = 'danger';
			$div_msg = 'Please enter a valid email address.';		
		} else { 
			// encrypt password (see documentation on php.net)		
			$options =['cost'=>HASHCOST];
			$user_pass = password_hash($user_pass1, PASSWORD_BCRYPT, $options);	
					
			move_uploaded_file($image_tmp, "../images/$user_image");
			
			$q = "UPDATE cms_users SET user_uname = '$user_uname', 
						user_pass = '$user_pass', user_fname = '$user_fname', 
						user_lname = '$user_lname', user_email = '$user_email',
						user_image = '$user_image', user_role = '$user_role', 
						user_status = '$user_status' WHERE user_id = $user_id";
			
			$result = mysqli_query($con, $q);
			
			$div_info = confirmQuery($result, 'update');
			$div_class 		= $div_info['div_class'];
			$div_msg 			= $div_info['div_msg'];
		}	
	} 
?>	
<?php 
	// ---------- if there is a user to be edited from $_GET -------------
	if(isset($_GET['id'])) {

		$edit_user_id = mysqli_real_escape_string($con, $_GET['id']);
		
		$q = "SELECT * FROM cms_users WHERE user_id = $edit_user_id";
		$result = mysqli_query($con, $q);
		$edit_user = mysqli_fetch_array($result);
		
		// this is a special case, so it does not user confirmQuery()
		// $div_msg may already exist, so don't overwrite it
		if(!$edit_user) {
			$div_class = "danger";
			$div_msg = "Database failed: ".mysqli_error($con);
		} elseif(empty($div_msg)) {
			$div_class = "success";
			$div_msg = 'User ready for edit.';
		}
	}
?>

<h2 class="page-title">Edit User:</h2>
<!---------------------------- alert div --------------------------- -->
<?php if($div_msg != ""):?>
<div class="row">
	<div class="col-md-12">
		<div class="alert alert-<?php echo $div_class;?>">
			<?php echo $div_msg;?>
		</div>
	</div>
</div>
<?php endif;?>

<!-- ------------------------- edit user form ---------------------- -->
<form action="" method="post" enctype="multipart/form-data">
	<!-- hidden field for user_id -->
	<input type="hidden" name="user_id" value="<?php echo $edit_user['user_id'];?>">
	<!-- hidden field for user_uname because that input is disabled -->
	<input type="hidden" name="user_uname" value="<?php echo $edit_user['user_uname'];?>">

	<div class="row">	<!-- ------------- 1st row --------------------- -->
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_uname_disabled">Create <a href="users.php?source=add_user">new user</a>
				to use a new username.
			</label>
			<input type="text" class="form-control" name="user_uname_disabled"
				value="<?php echo $edit_user['user_uname'];?>" disabled>
		</div>	
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_email">Email Address *</label>
			<input type="text" class="form-control" name="user_email"
				value="<?php echo $edit_user['user_email'];?>">
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
				value="<?php echo $edit_user['user_fname'];?>">
		</div>	
	</div>
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_lname">Last Name</label>
			<input type="text" class="form-control" name="user_lname"
				value="<?php echo $edit_user['user_lname'];?>">
		</div>
	</div>
	</div>
	
	<div class="row">	<!-- ------------- 4th row --------------------- -->
	<div class="col-md-6">	
		
	<div class="form-group" id="radios">
		<label for="user_image">User Image</label>
		<br />
		<?php
		// if current image exists display 3 radio buttons: current image, no
		// image, and new image; otherwise display only 2 radio buttons
		$current_image = $edit_user['user_image'];
		
		if($current_image != ""):?>
			<label>
			<input type="radio" name="user_image"
				value="<?php echo $current_image;?>" checked></label> 
				Current image: <?php echo $current_image;?>
			<img class="img-responsive center-block" height="150px" width="150px"
			src="../images/<?php echo $current_image;?>" alt="image">
		
			<label><input type="radio" name="user_image" value="none"> No image</label>
			<label><input type="radio" name="user_image" value="new"> New image</label>
		<?php else:?>
			<label><input type="radio" name="user_image" value="none" checked> No image</label>
			<label><input type="radio" name="user_image" value="new"> New image</label>
		<?php endif;?>
		
		<input id="file_input" type="file" accept="image/*" name="new_image">
	</div>	
	</div>		<!-- /.col-md-6 -->
	
	<div class="col-md-6">
		<div class="form-group">
			<label for="user_role">Role</label>
			
			<select name="user_role">
			<?php
				// loop thru each role to display select option; show any existing role as selected
				$roles = array('Subscriber','Administrator','Tourist');
			?>
			<?php	foreach($roles as $role):?>
			<?php 		if($role == $edit_user['user_role']):?>
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
			<?php 		if($status == $edit_user['user_status']):?>
				<option value="<?php echo $status;?>" selected><?php echo $status;?></option>
			<?php 		else:?>
				<option value="<?php echo $status;?>"><?php echo $status;?></option>
			<?php		endif;?>
			<?php	endforeach;?>
			</select>
		</div>	
	</div>
	</div>

	<button type="submit" name="updateusersubmit" class="btn btn-success add-del-btn">
		<i class="fa fa-database"></i> Update User</button>
		
	<?php if($_SESSION['role'] == 'Administrator'):?>				
	<a href="users.php" class="btn btn-primary">
			<i class="fa fa-eye"></i> View All Users</a>
	<?php endif;?>
</form>