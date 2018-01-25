<?php include 'admin_includes/admin_header.php';?>
<body>
	<div id="wrapper">
	<?php include 'admin_includes/admin_nav.php';?>

		<div id="page-wrapper">
			<div class="container-fluid">

				<!-- Page Heading -->
				<div class="row">
					<div class="col-md-12">
						<h1 class="page-header"><?php echo SITENAME;?> Admin
							<small id="small"> Profile Page for <?php echo $_SESSION['firstname'].' '.$_SESSION['lastname'];?></small>
						</h1>
					</div>
				</div>		<!-- /.row -->
									
				<div class="row">
					<div class="col-md-12">
<!-- ---------- only logged in users can use this page ----------------> 
<?php if(empty($_SESSION['userid'])):?>
	<h2>Sorry! You are not authorized to use this page.</h2>
	<a href="../index.php" class="btn btn-primary add-del-btn">Home</a>
	<a href="posts.php?source=" class="btn btn-primary add-del-btn">View Posts</a>
	<a href="../includes/logout.php" class="btn btn-primary add-del-btn">Log Out</a>
<?php else:?>					
					
<?php
// ----- get user data from cms_users table -----
$uid = $_SESSION['userid'];
$q = "SELECT * FROM cms_users WHERE user_id = $uid";
$result = mysqli_query($con, $q);
$user = mysqli_fetch_array($result);
$username = $user['user_uname'];

// ----- get total number of posts for user -----
$q = "SELECT COUNT(*) FROM cms_posts WHERE post_author = '$username'";
$result = mysqli_query($con, $q);
$num_posts = mysqli_fetch_array($result)[0];

// ----- get total number of comments for user -----
$q = "SELECT COUNT(*) FROM cms_comments WHERE comment_author = '$username'";
$result = mysqli_query($con, $q);
$num_comments = mysqli_fetch_array($result)[0];

?>
<form>
	<div class="row">	<!-- ------------- 1st row --------------------- -->
		<div class="col-md-6">
			<label>Username</label>
			<div class="well well-sm">
				<?php echo $user['user_uname'];?>
			</div>	
		</div>
		<div class="col-md-6">
			<label>Email</label>
			<div class="well well-sm">
				<?php echo $user['user_email'];?>
			</div>
		</div>
	</div>
	
	<div class="row">	<!-- ------------- 2nd row --------------------- -->
		<div class="col-md-6">
			<label>First Name</label>
			<div class="well well-sm">
				<?php echo $user['user_fname'];?>
			</div>	
		</div>
		<div class="col-md-6">
			<label>Last Name</label>
			<div class="well well-sm">
				<?php echo $user['user_lname'];?>
			</div>
		</div>
	</div>
	
	<div class="row">	<!-- ------------- 3rd row --------------------- -->
		<div class="col-md-6">	
			<label>Image</label>
			<div class="well">
				<img class="img-responsive center-block" height="163px" width="163px"
					src="../images/<?php echo $user['user_image'];?>" alt="image">
			</div>	
		</div>
		
		<div class="col-md-6">
			<div class="row">
				<div class="col-md-6">
					<label>Role</label>
					<div class="well well-sm">
						<?php echo $user['user_role'];?>
					</div>
				</div>
				<div class="col-md-6">
					<label>Status</label>
					<div class="well well-sm">
						<?php echo $user['user_status'];?>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<label>Number of Posts</label>
					<div class="well well-sm">
						<?php echo $num_posts;?>
					</div>
				</div>
				<div class="col-md-6">
					<label>Number of Comments</label>
					<div class="well well-sm">
						<?php echo $num_comments;?>
					</div>
				</div>
			</div>
			
			<div class="row">
				<div class="col-md-6">
					<label>Member Since</label>
					<div class="well well-sm">
					<?php date_default_timezone_set(TZ); ?>
						<?php echo date('M. j, Y, g:i a', strtotime($user['user_date']));?>
					</div>
				</div>
			</div>
		</div>		<!-- end of right column, 3rd row -->
	</div>		<!-- --------------------------------------------------- -->
	
	

	<a href="users.php?source=edit_user&id=<?php echo $user['user_id'];?>" 
		class="btn btn-success add-del-btn">
		<i class="fa fa-pencil"></i> Edit Profile</a>
		
	<?php if($_SESSION['role'] == 'Administrator'):?>
	<a href="users.php?source=" class="btn btn-primary add-del-btn">
		<i class="fa fa-eye"></i> View All Users</a>
	<?php endif;?>
</form>
<?php endif;?>		<!-- only 'Administrator' can use this page -->
<?php include 'admin_includes/admin_footer.php';?>