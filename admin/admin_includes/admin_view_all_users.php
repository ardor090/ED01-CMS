<!-- ---------- only 'Administrator' can use this page ----------------> 
<?php if($_SESSION['role'] != 'Administrator'):?>
	<h2>Sorry! You are not authorized to use this page.</h2>
	<a href="../index.php" class="btn btn-primary add-del-btn">Home</a>
	<a href="posts.php?source=" class="btn btn-primary add-del-btn">View Posts</a>
	<a href="profile.php" class="btn btn-primary add-del-btn">Profile</a>
	<a href="../includes/logout.php" class="btn btn-primary add-del-btn">Log Out</a>
<?php else:?>

<?php
	// if delete button is pressed (only an admin can do this)
	if(isset($_GET['del'])) {
		if($_SESSION['role'] == 'Administrator') {		
			$uid = mysqli_real_escape_string($con, $_GET['del']);
		
			$q = "DELETE FROM cms_users WHERE user_id = $uid";
		
			$del_result = mysqli_query($con, $q);
				
			$div_info = confirmQuery($del_result, 'delete');
			$div_class 		= $div_info['div_class'];
			$div_msg 			= $div_info['div_msg'];
		}
	}
?>	

<?php
	// if admin or subscriber button is pressed
	if(isset($_GET['role'])) {
		$new_role = mysqli_real_escape_string($con, $_GET['role']);
		$uid = $_GET['id'];
	
		$q = "UPDATE cms_users SET user_role = '$new_role'
					WHERE user_id = $uid";
	
		$del_result = mysqli_query($con, $q);
			
		$div_info = confirmQuery($del_result, 'update');
		$div_class 		= $div_info['div_class'];
		$div_msg 			= $div_info['div_msg'];
	}
?>	

<?php
	// display an alert message from result of $_GET or $_POST;
	// this row is not displayed if both $_GET and $_POST are not set
	if(isset($div_msg)):?>
		<div class="row">
			<div class="col-md-12">
				<div class="alert alert-<?php echo $div_class;?>">
					<?php echo $div_msg;?>
				</div>
			</div>
		</div>
<?php endif; ?>
				
<!-- displays all users in a table -->
<table class="table table-condensed table-bordered table-hover">
	<thead>
		<tr>
			<th style="width:4%;text-align:center">ID</th>
			<th style="width:8%;text-align:center">Username</th>
			<th style="width:11%;text-align:center">First Name</th>
			<th style="width:11%;text-align:center">Last Name</th>
			<th style="width:15%;text-align:center">Email</th>
			<th style="width:9%;text-align:center">Image</th>
			<th style="width:8%;text-align:center">Posts / Comments</th>
			<th style="width:10%;text-align:center">Start Date</th>
			<th style="width:7	%;text-align:center">Status</th>
			<th style="width:7%;text-align:center">Role</th>
			<th style="width:5%;text-align:center">Admin / No</th>
			<th style="width:5%;text-align:center">Edit / Delete</th>																	
		</tr>								
	</thead>
	<tbody>
	<?php
		$q = "SELECT cms_users.*,
					ifnull((SELECT count(cms_posts.post_id) 
						FROM cms_posts
            WHERE cms_users.user_uname = cms_posts.post_author
            GROUP BY cms_users.user_id),0) AS user_posts,
          ifnull((SELECT count(cms_comments.comment_id)
						FROM cms_comments
            WHERE cms_users.user_uname = cms_comments.comment_author
						GROUP BY cms_users.user_id),0) AS user_comments
					FROM cms_users
					ORDER BY cms_users.user_date DESC";

		$users = mysqli_query($con, $q);
	?>
	<?php foreach($users as $user):?>
		<tr>
			<td align="center"><?php echo $user['user_id'];?></td>
			<td><?php echo $user['user_uname'];?></td>
			<td><?php echo $user['user_fname'];?></td>
			<td><?php echo $user['user_lname'];?></td>
			<td><?php echo $user['user_email'];?></td>
			<td>
				<img class="img-responsive" style="margin:auto"
					src="../images/<?php echo $user['user_image'];?>" height="72px"	
					width="72px" alt="image">
			</td>
			<td align="center">
				<a href="../aposts.php?u=<?php echo $user['user_uname'];?>">
					<?php echo $user['user_posts'];?>
				</a> / 
				<a href="comments.php?author=<?php echo $user['user_uname'];?>">
					<?php echo $user['user_comments'];?>
				</a>
			</td>
			<?php date_default_timezone_set(TZ); ?>
			<td><?php echo date('M. j, Y, g:i a', strtotime($user['user_date']));?></td>
			<td><?php echo $user['user_status'];?></td>
			<td><?php echo $user['user_role'];?></td>
			<td align="center">
				<a href="users.php?role=Administrator&id=<?php echo $user['user_id'];?>" 
					class="btn btn-success btn-xs active" role="button">
					<abbr title="Administrator Role">
						<span class="glyphicon glyphicon-cog"></span>
					</abbr>
				</a>	
				<hr>
				<a href="users.php?role=Subscriber&id=<?php echo $user['user_id'];?>" 
					class="btn btn-warning btn-xs active" role="button">
					<abbr title="Subscriber Role">					
						<span class="glyphicon glyphicon-user"></span>
					</abbr>
				</a>		
			</td>
			<td align="center">
				<a href="users.php?source=edit_user&id=<?php echo $user['user_id'];?>" 
					class="btn btn-primary btn-xs active" role="button">
					<abbr title="Edit User">
						<span class="glyphicon glyphicon-pencil"></span>
					</abbr>
				</a>	
				<hr>
				<a onclick="return confirm('Are you sure you want to delete	 this user?');" 
					href="users.php?del=<?php echo $user['user_id'];?>" 
					class="btn btn-danger btn-xs active" role="button">
					<abbr title="Delete User">
						<span class="glyphicon glyphicon-trash"></span>
					</abbr>
				</a>		
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>	
<?php endif;?>		<!-- only 'Administrator' can use this page -->