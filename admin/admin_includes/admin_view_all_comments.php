<!-- ---------- only logged in users can use this page ----------------> 
<?php if(empty($_SESSION['userid'])):?>
	<h2>Sorry! You are not authorized to use this page.</h2>
	<a href="../index.php" class="btn btn-primary add-del-btn">Home</a>
	<a href="posts.php?source=" class="btn btn-primary add-del-btn">View Posts</a>
	<a href="profile.php" class="btn btn-primary add-del-btn">Profile</a>
	<a href="../includes/logout.php" class="btn btn-primary add-del-btn">Log Out</a>
<?php else:?>	

<?php
	// if delete button is pressed, admins can delete any comment 
	// but others can only delete their own comments
	if(isset($_GET['del'])) {
		if($_SESSION['role'] == 'Administrator' ||
			(isset($_GET['author']) && $_SESSION['username'] == $_GET['author'])) {
			$cid = mysqli_real_escape_string($con, $_GET['del']);
		
			$q = "DELETE FROM cms_comments WHERE comment_id = $cid";
		
			$del_result = mysqli_query($con, $q);
				
			$div_info = confirmQuery($del_result, 'delete');
			$div_class 		= $div_info['div_class'];
			$div_msg 			= $div_info['div_msg'];
		}
	}
	
?>	

<?php
	// if like or dislike button is pressed
	if(isset($_GET['like'])) {
		$status		= mysqli_real_escape_string($con, $_GET['like']);
		$cid				= mysqli_real_escape_string($con, $_GET['cid']);
	
		$q = "UPDATE cms_comments SET comment_status = '$status'
					WHERE comment_id = $cid";
	
		$del_result = mysqli_query($con, $q);
			
		$div_info = confirmQuery($del_result, 'update');
		$div_class 		= $div_info['div_class'];
		$div_msg 			= $div_info['div_msg'];
	}
?>

<?php
	if(isset($_GET['pid'])) {
		$pid = mysqli_real_escape_string($con, $_GET['pid']);
		$q = "SELECT cms_comments.*, cms_posts.post_id, cms_posts.post_title,
						cms_posts.post_author
					FROM cms_comments
					INNER JOIN cms_posts
					ON cms_comments.comment_post_id = cms_posts.post_id
					WHERE cms_comments.comment_post_id = $pid
					ORDER BY cms_comments.comment_date DESC";
					
		$div_msg = "Showing comments for this post. ";
		// can not allow non-admin to see all comments 
		if($_SESSION['role'] == 'Administrator') {
			$div_msg .= "<a href='comments.php'>Show All</a>";
		}
	} elseif(isset($_GET['author'])) {
		$author = mysqli_real_escape_string($con, $_GET['author']);
		
		// this is to prevent a non-admin from setting $_GET to another author
		if($_SESSION['role'] != 'Administrator') {
			$author = $_SESSION['username'];
		}
		
		$q = "SELECT cms_comments.*, cms_posts.post_id, cms_posts.post_title,
						cms_posts.post_author
					FROM cms_comments
					INNER JOIN cms_posts
					ON cms_comments.comment_post_id = cms_posts.post_id
					WHERE cms_comments.comment_author = '$author'
					ORDER BY cms_comments.comment_date DESC";
		
		$div_msg = "Showing comments for '$author'. ";
		// can not allow non-admin to see all comments 
		if($_SESSION['role'] == 'Administrator') {
			$div_msg .= "<a href='comments.php'>Show All</a>";
		}
	} else {
		$q = "SELECT cms_comments.*, cms_posts.post_id, cms_posts.post_title,
						cms_posts.post_author 
					FROM cms_comments
					INNER JOIN cms_posts
					ON cms_comments.comment_post_id = cms_posts.post_id
					ORDER BY cms_comments.comment_date DESC";
					$div_msg = "Showing all comments.";
	}
	
	$comments = mysqli_query($con, $q);
	
	if(!$comments) {
		$div_class = 'danger';
		$div_msg = "Database error: " . mysqli_error($con);	
	} else {
		$div_class = 'success';
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
				
<!-- displays all comments in a table -->
<table class="table table-condensed table-bordered table-hover">
	<thead>
		<tr>
			<th style="width:4%;text-align:center">ID</th>
			<th style="width:14%;text-align:center">Author</th>
			<th style="width:32%;text-align:center">Comment</th>
			<th style="width:21%;text-align:center">In Response To Post</th>
			<th style="width:10%;text-align:center">Date</th>
			<th style="width:7%;text-align:center">Status</th>			
			<th style="width:6%;text-align:center">Like / Dislike</th>
			<th style="width:6%;text-align:center">Delete</th>																		
		</tr>								
	</thead>
	<tbody>	
	<?php foreach($comments as $comment):?>
		<tr>
			<td align="center"><?php echo $comment['comment_id'];?></td>
			<td><a href="comments.php?author=<?php echo $comment['comment_author'];?>">
				<?php echo $comment['comment_author'];?></a>
			</td>
			<td>	<?php echo $comment['comment_content'];?></td>
			<td><a href="../post.php?pid=<?php echo $comment['post_id'];?>">
				<?php echo $comment['post_title'];?></a></td>
			<?php date_default_timezone_set(TZ); ?>
			<td><?php echo date('M. j, Y, g:i a', strtotime($comment['comment_date']));?></td>
			<td><?php echo $comment['comment_status'];?></td>
			
			<?php
				// set up string for href attribute depending on like/dislike/delete
				// comment for a single post, single author, or all by attaching the
				// appropriate GET request
				$href1 = "comments.php?";
				if(!empty($pid)) {
					$href3 = "&pid=$pid";
				} elseif(!empty($author)) {
					$href3 = "&author=$author";
				} else {
					$href3 = "";
					$author = "";
				}
				$like_href = $href1."like=like&cid=".$comment['comment_id'].$href3;
				$dislike_href = $href1."like=dislike&cid=".$comment['comment_id'].$href3;
				$delete_href = $href1."del=".$comment['comment_id'].$href3;
			?>	
			<td align="center">
				<?php if($_SESSION['role'] == 'Administrator' || 
								$comment['post_author'] == $_SESSION['username']):?>
				<a href="<?php echo $like_href;?>" 
					class="btn btn-success btn-xs active" role="button">
					<abbr title="Like Comment">
					<span class="glyphicon glyphicon-thumbs-up"></span>
					</abbr>
				</a>	
				<hr>
				<a href="<?php echo $dislike_href;?>" 
					class="btn btn-danger btn-xs active" role="button">
					<abbr title="Dislike Comment">
					<span class="glyphicon glyphicon-thumbs-down"></span>
					</abbr>
				</a>	
				<?php else:?>
				Poster / Admin Only
				<?php endif;?>
			</td>
			<td align="center">
				<a onclick="return confirm('Are you sure you want to delete this comment?');"	
					href="<?php echo $delete_href;?>" 
					class="btn btn-danger btn-xs active" role="button">
					<abbr title="Delete Comment">
					<span class="glyphicon glyphicon-trash"></span>
					</abbr>
				</a>	
			</td>
		</tr>
	<?php endforeach;?>
	</tbody>
</table>	
<?php endif;?>		<!-- only 'Administrator' can use this page -->