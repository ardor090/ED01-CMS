<?php include 'admin_includes/admin_header.php';?>
		
			<body>
				<div id="wrapper">
		
				<?php include 'admin_includes/admin_nav.php';?>
		
					<div id="page-wrapper">
						<div class="container-fluid">
		
							<!-- Page Heading -->
							<div class="row admin-header">
								<div class="col-lg-12">
									<h1 class="page-header"><?php echo SITENAME;?> Admin
										<small id="small"> Welcome <?php echo $_SESSION['firstname'];?> !</small>
									</h1>

<!-- ---------- only 'Administrator' can use this page ----------------> 
<?php if($_SESSION['role'] != 'Administrator'):?>
	<h2>Sorry! You are not authorized to use this page.</h2>
	<a href="../index.php" class="btn btn-primary add-del-btn">Home</a>
	<a href="posts.php?source=" class="btn btn-primary add-del-btn">View Posts</a>
	<a href="profile.php" class="btn btn-primary add-del-btn">Profile</a>
	<a href="../includes/logout.php" class="btn btn-primary add-del-btn">Log Out</a>
<?php else:?>

<!-- --------------------------------------------------------------- -->

<div class="row">
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-primary">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-file-text fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">		
<?php
$q = "SELECT * FROM cms_posts";
$result = mysqli_query($con, $q);
$num_posts = mysqli_num_rows($result);
?>
						<div class='huge'><?php echo $num_posts;?></div>
						<div>Posts</div>
					</div>
				</div>
			</div>
			<a href="posts.php">
				<div class="panel-footer">
					<span class="pull-left">View Posts</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
	
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-green">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-comments fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
<?php
$q = "SELECT * FROM cms_comments";
$result = mysqli_query($con, $q);
$num_comments = mysqli_num_rows($result);
?>
	 					<div class='huge'><?php echo $num_comments;?></div>
						<div>Comments</div>
					</div>
				</div>
			</div>
			<a href="comments.php">
				<div class="panel-footer">
					<span class="pull-left">View Comments</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
			</div>
	</div>
    
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-yellow">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-user fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
<?php
$q = "SELECT * FROM cms_users";
$result = mysqli_query($con, $q);
$num_users = mysqli_num_rows($result);
?>
						<div class='huge'><?php echo $num_users;?></div>
						<div> Users</div>
					</div>	
				</div>
			</div>
			<a href="users.php">
				<div class="panel-footer">
					<span class="pull-left">View Users</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
    
	<div class="col-lg-3 col-md-6">
		<div class="panel panel-red">
			<div class="panel-heading">
				<div class="row">
					<div class="col-xs-3">
						<i class="fa fa-list fa-5x"></i>
					</div>
					<div class="col-xs-9 text-right">
<?php
$q = "SELECT * FROM cms_categories";
$result = mysqli_query($con, $q);
$num_cats = mysqli_num_rows($result);
?>
						<div class='huge'><?php echo $num_cats;?></div>
						<div>Categories</div>
					</div>
				</div>
			</div>
			<a href="categories.php">
				<div class="panel-footer">
					<span class="pull-left">View Categories</span>
					<span class="pull-right"><i class="fa fa-arrow-circle-right"></i></span>
					<div class="clearfix"></div>
				</div>
			</a>
		</div>
	</div>
</div>		<!-- /.row of panels -->
<!-- my own chart 
<?php $p = $num_posts*100/20;?>
<div class="row">
	<div class="col-md-12" style="background:red;height:30px;width:<?php echo $p;?>%">
		<p style="color:white;font-size:16px;margin:0;padding:7px;">
			<?php echo $num_posts.' posts';?>
		</p>
	</div>
</div>
-->
<?php
// get additional data for graph
$q = "SELECT * FROM cms_posts WHERE post_status = 'Published'";
$result = mysqli_query($con, $q);
$num_pubs = mysqli_num_rows($result);

$q = "SELECT * FROM cms_posts WHERE post_status = 'Draft'";
$result = mysqli_query($con, $q);
$num_drafts = mysqli_num_rows($result);

$q = "SELECT * FROM cms_comments WHERE comment_status = 'dislike'";
$result = mysqli_query($con, $q);
$num_dislike = mysqli_num_rows($result);

$q = "SELECT * FROM cms_users WHERE user_role = 'Subscriber'";
$result = mysqli_query($con, $q);
$num_subs = mysqli_num_rows($result);

?>

<!-- ------------------ Google Charts ------------------------------ -->
<script>
  google.load("visualization", "1.1", {packages:["bar"]});
  google.setOnLoadCallback(drawChart);
  function drawChart() {
    var data = google.visualization.arrayToDataTable([
      ['Data', 'count'],
      
/*******************************************/
<?php
$mydata = [	'Total Posts' => $num_posts,
						'Published Posts' => $num_pubs,
						'Draft Posts' => $num_drafts,
						'Total Comments' => $num_comments,
						'Disliked Comments' => $num_dislike,
						'Total Users' => $num_users,
						'Subscribers' => $num_subs,
						'Categories' => $num_cats];
		
foreach($mydata as $key => $value) {
	echo "['" . $key . "', " . $value . "],";
}
?>
/*******************************************/

    ]);

    var options = {
      chart: {
        title: '<?php echo SITENAME;?> Snapshot',
        subtitle: '',
      }
    };

    var chart = new google.charts.Bar(document.getElementById('columnchart_material'));

    chart.draw(data, options);
  }
</script>

<div id="columnchart_material" style="width:auto; height:500px;"></div>

<!-- --------------------------------------------------------------- -->
<?php endif;?>		<!-- only 'Administrator' can use this page -->
  </body>

<?php include 'admin_includes/admin_footer.php';?>