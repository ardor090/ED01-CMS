<?php
	// -------------- if 'Update Post' button is pressed -----------------
	if(isset($_POST['updatepostsubmit'])) {

		$update_post_id = $_POST['post_id'];
		$author = mysqli_real_escape_string($con, $_POST['post_author']);
		$title = mysqli_real_escape_string($con, $_POST['post_title']);
		$category = $_POST['post_category'];
		$status = mysqli_real_escape_string($con, $_POST['post_status']);
		$tags = mysqli_real_escape_string($con, $_POST['post_tags']);
		$content = mysqli_real_escape_string($con, $_POST['post_content']);
		
		// set the image name to the result of the radio button input		
		switch($_POST['post_image']) {
			case "none":
				$image = "";
				break;
			case "new":
				$image = $_FILES['new_image']['name'];
				break;
			default:
				$image = $_POST['post_image'];		
		}
		
		$image_tmp = $_FILES['new_image']['tmp_name'];
		move_uploaded_file($image_tmp, "../images/$image");
		
		$q = "UPDATE cms_posts SET post_author = '$author',
					post_title = '$title', post_cat_id = $category,
					post_status = '$status', post_tags = '$tags', 
					post_image = '$image', 	post_content = '$content' 
					WHERE post_id = $update_post_id";
		$update_result = mysqli_query($con, $q);
		
		$div_info = confirmQuery($update_result, 'update');
		$div_class 		= $div_info['div_class'];
		$div_msg 			= $div_info['div_msg']; 

	} 
?>	
<?php 
	// ------------ if there is a post to be edited from $_GET -----------
	if(isset($_GET['id'])) {

		$edit_post_id = $_GET['id'];
		
		$q = "SELECT * FROM cms_posts WHERE post_id = $edit_post_id";
		$result = mysqli_query($con, $q);
		$edit_post = mysqli_fetch_array($result);
		
		// this is a special case, so it does not user confirmQuery()
		if(!$edit_post) {
			$div_class = "danger";
			$div_msg = "Database failed: ".mysqli_error($con);
		} elseif(empty($div_msg)) {
			$div_class = "success";
			$div_msg = 'Post ready for edit.';
		}
	}
?>

<?php 
	// get category name from databae for dropdown field
	$select_cat_id = $edit_post['post_cat_id'];
	
	$q = "SELECT * FROM cms_categories ORDER BY cat_title";
	$cats = mysqli_query($con, $q);
	
	// this is a special case, so it does not user confirmQuery()
	if(!$cats) {
		$div_class = "danger";
		$div_msg = "Database failed: ".mysqli_error($con);
	} elseif($div_msg == "") {
		$div_class = "success";
		$div_msg = 'Post ready for edit.';
	}
?>

<h2 class="page-title">Edit Post:</h2>
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
<!-- --------------------- begin form ------------------------------ -->
<form action="" method="post" enctype="multipart/form-data">
	<!-- hidden input field to hold post_id -->
	<input type="hidden" name="post_id" value="<?php echo $edit_post['post_id'];?>">
	<!-- hidden input field to hold post_author -->
	<input type="hidden" name="post_author" value="<?php echo $edit_post['post_author'];?>">
	 
	<div class="form-group">
		<label for="post_author_disabled">Post Author username can not be changed</label>
		<input type="text" class="form-control" name="post_author_disabled"
			value="<?php echo $edit_post['post_author'];?>" disabled>
	</div>	
	<div class="form-group">
		<label for="post_title">Post Title</label>
		<input type="text" class="form-control" name="post_title"
			value="<?php echo $edit_post['post_title'];?>">
	</div>
	

	<div class="row">	<!-- ---------- left section ------------------- -->
	<div class="col-md-4">
	
	<div class="form-group">
		<label for="post_category">Post Category </label>
		<select name="post_category">
			<?php if(isset($cats)):?>
				<?php foreach($cats as $cat):?>
					<?php if($cat['cat_id']==$select_cat_id):?>
						<option value="<?php echo $cat['cat_id'];?>" selected>
							<?php echo $cat['cat_title'];?></option>
					<?php else:?>
						<option value="<?php echo $cat['cat_id'];?>">
							<?php echo $cat['cat_title'];?></option>
					<?php endif;?>
				<?php endforeach;?>
			<?php endif;?>
		</select>	
	</div>
	
	<div class="form-group">
		<label for="post_status">Post Status</label>
		<select name="post_status">
		<?php if($edit_post['post_status'] == 'Draft'):?>
			<?php echo '<option value="Draft" selected>Draft</option>';?>
			<?php echo '<option value="Published">Publish</option>';?>
		<?php else:?>
			<?php echo '<option value="Draft">Draft</option>';?>
			<?php echo '<option value="Published" selected>Publish</option>';?>
		<?php endif;?>
		</select>
	</div>
	
	<div class="form-group">
		<label for="post_tags">Post Tags</label>
		<input type="text" class="form-control" name="post_tags"
			value="<?php echo $edit_post['post_tags'];?>">
	</div>	
	<div class="form-group" id="radios">
		<label for="post_image">Post Image</label>
		<br />
		<?php
		// if current image exists display 3 radio buttons: current image, no
		// image, and new image; otherwise display only 2 radio buttons
		$current_image = $edit_post['post_image'];
		
		if($current_image != ""):?>
			<label>
			<input type="radio" name="post_image"
				value="<?php echo $current_image;?>" checked></label> 
				Current image: <?php echo $current_image;?>
			<img class="img-responsive center-block" height="70px" width="210px"
			src="../images/<?php echo $current_image;?>" alt="image">
		
			<label><input type="radio" name="post_image" value="none"> No image</label>
			<label><input type="radio" name="post_image" value="new"> New image</label>
		<?php else:?>
			<label><input type="radio" name="post_image" value="none" checked> No image</label>
			<label><input type="radio" name="post_image" value="new"> New image</label>
		<?php endif;?>
		
		<input id="file_input" type="file" accept="image/*" name="new_image">
	</div>
	
	
	</div>		<!-- /.col-md-4 ---------- right section --------------- -->
	<div class="col-md-8">
	
	
	<div class="form-group">
		<label for="post_content">Post Content</label>
		<textarea class="form-control" name="post_content"  
			rows="19"><?php echo $edit_post['post_content'];?></textarea>
	</div>
	
	</div>		<!-- /.col-md-8 -->
	</div>		<!-- /.row --------------------------------------------- -->
	
	<button type="submit" name="updatepostsubmit" class="btn btn-success add-del-btn">
		<i class="fa fa-database"></i> Update Post</button>
	<a href="posts.php" class="btn btn-primary">
			<i class="fa fa-eye"></i> View All Posts</a>
</form>
