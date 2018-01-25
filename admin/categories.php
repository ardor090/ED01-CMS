<?php include 'admin_includes/admin_header.php';?>
<?php include 'admin_includes/admin_cat_logic.php';?>

<body>
	<div id="wrapper">
	<?php include 'admin_includes/admin_nav.php';?>

		<div id="page-wrapper">
			<div class="container-fluid">

				<!-- Page Heading -->
				<div class="row">
					<div class="col-md-12">
						<h1 class="page-header"><?php echo SITENAME;?> Admin
							<small id="small"> Category Manager</small>
						</h1>
					</div>
				</div>		<!-- /.row -->
				
				<!-- --------- only 'Administrator' can use this page ------ -->
				<?php if($_SESSION['role'] != 'Administrator'):?>
				<h2>Sorry! You are not authorized to use this page.</h2>
				<a href="../index.php" class="btn btn-primary add-del-btn">Home</a>
				<a href="posts.php?source=" class="btn btn-primary add-del-btn">View Posts</a>
				<a href="profile.php" class="btn btn-primary add-del-btn">Profile</a>
				<a href="../includes/logout.php" class="btn btn-primary add-del-btn">Log Out</a>
				<?php else:?>
				
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
									
				<div class="row">
					<div class="col-md-6">		
						<table class="table table-condensed">
							<thead>
								<tr>
									<th>Category ID</th>
									<th>Category Title</th>
									<th></th>
									<th></th>	
								</tr>												
							</thead>											
							<tbody>
							<?php
								// get categories to display in table which will include any
								// newly inserted categories
								$q = "SELECT * FROM cms_categories ORDER BY cat_title";
								$cats = mysqli_query($con, $q);
							?>					
							<?php foreach($cats as $cat):?>
								<tr>
									<td><?php echo $cat['cat_id'];?></td>
									<td><?php echo $cat['cat_title'];?></td>
									<td><a class="btn btn-primary"
										href="categories.php?edit=<?php echo $cat['cat_id'];?>">
										<i class="fa fa-pencil"></i> Edit</a>
									</td>	
									<td><a onclick="return confirm('Are you sure you want to delete this category?');" 
										class="btn btn-danger"
										href="categories.php?del=<?php echo $cat['cat_id'];?>">
										<i class="fa fa-trash-o"></i> Delete</a>
									</td>													
								</tr>
							<?php endforeach;?>
							</tbody>									
						</table>
					</div>	
				
					<div class="col-md-6">
						<!-- form to add new category -->
						<form action="categories.php" method="post">
							<div class="form-group1">
								<label for="cat_title">Add Category Title: </label>
								<input class="form-control" type="text" name="add_cat_title">
							</div>
							<div class="form-group1">
								<button type="submit" name="addsubmit" class="btn btn-success">
									<i class="fa fa-plus"></i> Add Category
								</button>
							</div>
						</form>
						<hr />
						<!-- form to edit category -->
						<form action="categories.php" method="post">
							<div class="form-group2">
								<label for="edit_cat_title">Edit Category Title: </label>
								<input class="form-control" type="text" name="edit_cat_title"
									value="<?php echo $edit_cat['cat_title'];?>">
							</div>
							<div class="form-group2">
								<input class="form-control" type="hidden" name="edit_cat_id"
									value="<?php echo $edit_cat['cat_id'];?>">
							</div>
							<div class="form-group2">
								<button type="submit" name="editsubmit" class="btn btn-success"
									<?php echo $btn_disabled;?>>
									<i class="fa fa-database"></i> Update Category
								</button>
							</div>
						</form>											
				<?php endif;?>		<!-- only 'Administrator' can use this page -->
<?php include 'admin_includes/admin_footer.php';?>