<!-- ---------- only logged in user can use this page ----------------> 
<?php if(empty($_SESSION['userid'])):?>
	<h2>Sorry! You are not authorized to use this page.</h2>
	<a href="../index.php" class="btn btn-primary add-del-btn">Home</a>
	<a href="posts.php?source=" class="btn btn-primary add-del-btn">View Posts</a>
	<a href="profile.php" class="btn btn-primary add-del-btn">Profile</a>
	<a href="../includes/logout.php" class="btn btn-primary add-del-btn">Log Out</a>
<?php else:?>	

<?php
	// if delete button is pressed, admins can delete any post 
	// but others can only delete their own posts
	if(isset($_GET['del'])) {
		if($_SESSION['role'] == 'Administrator' ||
			(isset($_GET['author']) && $_SESSION['username'] == $_GET['author'])) {
			$id = mysqli_real_escape_string($con, $_GET['del']);
		
			$q = "DELETE FROM cms_posts WHERE post_id = $id";
		
			$del_result = mysqli_query($con, $q);
				
			$div_info = confirmQuery($del_result, 'delete');
			$div_class 		= $div_info['div_class'];
			$div_msg 			= $div_info['div_msg'];
		}
	}
?>	
<?php
	// if 'Apply' button is pressed and $_POST is set, check that a
	// bulk action is selected and at least one post_id is selected;
	// otherwise, don't do anything
	if(!empty($_POST['bulkaction']) && !empty($_POST['cbarray'])) {
		// get action from dropdown list
		$bulk_action = $_POST['bulkaction'];
		
		// loop thru each checked item to build a list of post_id's
		// that looks like '(nn,nn,nn,nn)'
		$pid_list = '(';
		foreach($_POST['cbarray'] as $pid) {
			$pid_list .= $pid . ',';
		}
		// remove the last ',' and add a ')'
		$pid_list = substr($pid_list, 0, strrpos($pid_list, ',')) . ')';
		
		switch($bulk_action) {
			case 'Set Published Status':		
				$q = "UPDATE cms_posts SET post_status = 'Published'
							WHERE post_id in $pid_list";
				break;
			case 'Set Draft Status':
				$q = "UPDATE cms_posts SET post_status = 'Draft' 
							WHERE post_id in $pid_list";
				break;
			case 'Clone':
				$q = "INSERT INTO cms_posts (post_cat_id, post_title, post_author, 
							post_image, post_content, post_tags, post_status) 
							SELECT post_cat_id, post_title, post_author,post_image, post_content, 
							post_tags, post_status 
							FROM cms_posts WHERE post_id in $pid_list";
				break;
			case 'Delete':
				$q = "DELETE FROM cms_posts WHERE post_id in $pid_list";
				break;
			default:
				break;	
		}
		
		$result = mysqli_query($con, $q);
		$div_info = confirmQuery($result, $bulk_action);
		$div_class 		= $div_info['div_class'];
		$div_msg 			= $div_info['div_msg'];	
	}

?>

<form action="" method="post">

	<div class="row">
		<!-- 'view_posts_bulk' class is for setting the upper margins in order
					to line up with the alert box on the right -->
		<div class="col-md-3 view-posts-bulk">
			<select class="form-control" name="bulkaction">
				<option value="">Select Bulk Action</option>
				<option value="Set Published Status">Set 'Published' Status</option>
				<option value="Set Draft Status">Set 'Draft' Status</option>
				<option value="Clone">Clone Posts</option>
				<option value="Delete">Delete Posts</option>
			</select>
			<span id="view-posts-arrow" class="glyphicon glyphicon-arrow-down"></span>
		</div>
		<div class="col-md-4 view-posts-bulk">
			<input type="submit" name="bulksubmit" class="btn btn-success" value="Apply"
				onclick="javascript: return confirm('Are you sure?');">
			<a href="posts.php?source=add_post" class="btn btn-primary">Add New</a>
			<a href="posts.php?source=" class="btn btn-default">Refresh</a>
		</div>
		<?php
		// display an alert message from result of $_GET or $_POST;
		// this row is not displayed if both $_GET and $_POST are not set
		if(isset($div_msg)):?>
		<div class="col-md-5">
			<div class="alert alert-<?php echo $div_class;?>">
				<?php echo $div_msg;?>
			</div>
		</div>
		<?php endif; ?>	
	</div>		<!-- /.row -->
	
	<!-- displays all posts in a table -->
	<table class="table table-condensed table-bordered table-hover"
		style="margin-top:20px;">
		<thead>
			<tr>
				<th style="width:4%;text-align:center">
					<input id="select_all" type="checkbox"></th>
				<th style="width:4%;text-align:center">ID</th>
				<th style="width:8%;text-align:center">Author</th>
				<th style="width:17%;text-align:center">Title</th>
				<th style="width:7%;text-align:center">Category</th>
				<th style="width:7%;text-align:center">Status</th>
				<th style="width:17%;text-align:center">Image</th>
				<th style="width:16%;text-align:center">Tags</th>
				<th style="width:9%;text-align:center">Views / Comments</th>
				<th style="width:10%;text-align:center">Date</th>
				<th style="width:5%;text-align:center">Edit / Delete</th>																		
			</tr>								
		</thead>
		<tbody>
		<?php	// non-admins can only see their own posts
		if($_SESSION['role'] == 'Administrator') {	
			$author = "";	
			$q = "SELECT cms_posts.*, cms_categories.cat_title, 
						count(cms_comments.comment_id) as	post_comment_count
						FROM cms_posts
						INNER JOIN cms_categories ON cms_posts.post_cat_id = cms_categories.cat_id
						LEFT OUTER JOIN cms_comments ON cms_posts.post_id = cms_comments.comment_post_id
						GROUP BY cms_posts.post_id
						ORDER BY cms_posts.post_date DESC";
		} else {
			$author = $_SESSION['username'];			
			$q = "SELECT cms_posts.*, cms_categories.cat_title, 
						count(cms_comments.comment_id) as	post_comment_count
						FROM cms_posts
						INNER JOIN cms_categories ON cms_posts.post_cat_id = cms_categories.cat_id
						LEFT OUTER JOIN cms_comments ON cms_posts.post_id = cms_comments.comment_post_id
						WHERE post_author = '$author'
						GROUP BY cms_posts.post_id
						ORDER BY cms_posts.post_date DESC";
		}

		$posts = mysqli_query($con, $q);
		?>
		<?php foreach($posts as $post):?>
			<tr>
				<!-- 'checkbox_column' class centers all checkboxes in custom.css-->
				<td class="checkbox_column">
					<input class="checkboxes" type="checkbox" name="cbarray[]"
						value="<?php echo $post['post_id'];?>">
				</td>
				<td align="center"><?php echo $post['post_id'];?></td>
				<td><?php echo $post['post_author'];?></td>
				<td>
					<a href="../post.php?pid=<?php echo $post['post_id'];?>">	
						<?php echo $post['post_title'];?></a>
				</td>
				<td><?php echo $post['cat_title'];?></td>
				<td><?php echo $post['post_status'];?></td>
				<td>
					<img class="img-responsive" style="margin:auto"
						src="../images/<?php echo $post['post_image'];?>" height="47px"	
						width="141px" alt="image">
				</td>
				<td><?php echo $post['post_tags'];?></td>
				<td align="center"><?php echo $post['post_views_count'];?> / 
					<a href="comments.php?pid=<?php echo $post['post_id'];?>">				
						<?php echo $post['post_comment_count'];?></a>
				</td>
				<?php date_default_timezone_set(TZ); ?>
				<td><?php echo date('M. j, Y, g:i a', strtotime($post['post_date']));?>
				</td>
				<td align="center">
					<a href="posts.php?source=edit_post&id=<?php echo $post['post_id'];?>" 
						class="btn btn-primary btn-xs active" role="button">
						<abbr title="Edit Post">
							<span class="glyphicon glyphicon-pencil"></span>
						</abbr>
					</a>	
					<hr>
					<a onclick="return confirm('Are you sure you want to delete	 this post and all its comments?');" 
						href="posts.php?del=<?php echo $post['post_id'];?>&author=<?php echo $author;?>" 
						class="btn btn-danger btn-xs active" role="button">
						<abbr title="Delete Post">
							<span class="glyphicon glyphicon-trash"></span>
						</abbr>
					</a>		
				</td>
			</tr>
		<?php endforeach;?>
		</tbody>
	</table>	
</form>
<?php endif;?>		<!-- only 'Administrator' can use this page -->