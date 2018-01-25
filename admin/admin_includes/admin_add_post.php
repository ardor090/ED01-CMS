<?php
	if(isset($_POST['addpostsubmit'])) {
		$author = mysqli_real_escape_string($con, $_POST['post_author']);
		$title = mysqli_real_escape_string($con, $_POST['post_title']);
		$cat = $_POST['post_category'];
		$status = mysqli_real_escape_string($con, $_POST['post_status']);
		$tags = mysqli_real_escape_string($con, $_POST['post_tags']);
		$image = mysqli_real_escape_string($con, $_FILES['post_image']['name']);
		$image_tmp = $_FILES['post_image']['tmp_name'];
		$content = mysqli_real_escape_string($con, $_POST['post_content']);
		
		move_uploaded_file($image_tmp, "../images/$image");
		
		$q = "INSERT INTO cms_posts
				(post_cat_id, post_title, post_author, post_date, post_image,
				post_content, post_tags, post_status)
				VALUES ($cat, '$title', '$author', now(), '$image', '$content',
				'$tags', '$status')";
		
		$result = mysqli_query($con, $q);
		
		$div_info = confirmQuery($result, 'insert');
		$div_class 		= $div_info['div_class'];
		$div_msg 			= $div_info['div_msg'];
	}

?>

<?php 
	// get category name from databae for dropdown field
	
	$q = "SELECT * FROM cms_categories ORDER BY cat_title";
	$cats = mysqli_query($con, $q);
	
	// this is a special case, so it does not user confirmQuery()
	if(!$cats) {
		$div_class = "danger";
		$div_msg = "Database failed: ".mysqli_error($con);
	}
?>

<h2 class="page-title">Add Post:</h2>
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

<?php
	if(!empty($_SESSION['username'])) {
		$author = $_SESSION['username'];
	} else {
		header('Location: ../index.php');
	}
?>
<!-- ------------------------- begin form -------------------------- -->
<form action="" method="post" enctype="multipart/form-data">
	<!-- hidden input field to hold post_author -->
	<input type="hidden" name="post_author" value="<?php echo $author;?>">
	
	<div class="form-group">
		<label for="post_author">Post Author (You may not post under a different name.)</label>
		<input type="text" class="form-control" name="post_author_disabled"
			value="<?php echo $author?>" disabled>
	</div>	
	<div class="form-group">
		<label for="post_title">Post Title</label>
		<input type="text" class="form-control" name="post_title">
	</div>
	
	
	<div class="row">	<!-- ------------------------------------------- -->
	<div class="col-md-4">	
	
	
	<div class="form-group">
		<label for="post_category">Post Category</label>
			<select name="post_category">
			<?php if(isset($cats)):?>
				<?php foreach($cats as $cat):?>
					<option value="<?php echo $cat['cat_id'];?>">
							<?php echo $cat['cat_title'];?></option>
				<?php endforeach;?>
			<?php endif;?>
		</select>			
	</div>
	<div class="form-group">
		<label for="post_status">Post Status</label>
		<select name="post_status">
			<option value="Draft">Draft</option>
			<option value="Published">Publish</option>
		</select>
	</div>
	<div class="form-group">
		<label for="post_tags">Post Tags</label>
		<input type="text" class="form-control" name="post_tags">
	</div>
	<div class="form-group">
		<label for="post_image">Post Image</label>
		<input type="file" accept="image/*" name="post_image">
	</div>
	
	</div>		<!-- /.col-md-4 ---------------------------------------- -->
	<div class="col-md-8">
	
	
	<div class="form-group">
		<label for="title">Post Content</label>
		<textarea class="form-control" name="post_content" rows="16"></textarea>
	</div>
	
	</div>		<!-- /.col-md-8 -->
	</div>		<!-- /.row --------------------------------------------- -->
	
	
	<button type="submit" name="addpostsubmit" class="btn btn-success add-del-btn">
		<i class="fa fa-plus"></i> Add Post</button>
	<a href="posts.php" class="btn btn-primary">
			<i class="fa fa-eye"></i> View All Posts</a>
			
</form>