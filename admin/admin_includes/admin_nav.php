<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<!-- Brand and toggle get grouped for better mobile display -->
	<div class="navbar-header">
		<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-ex1-collapse">
		  <span class="sr-only">Toggle navigation</span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		  <span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="index.php"><?php echo SITENAME;?> Admin</a>
	</div>
	
	<!-- Top Menu Items -->
	<ul class="nav navbar-right top-nav">
		<li><a href="#">Users Online: <?php echo users_online();?></a></li> 
		<li><a href="../index.php">Home</a></li>
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<i class="fa fa-user fa-lg"></i> 
					<?php echo $_SESSION['firstname'].' '.$_SESSION['lastname'];?>
				<b class="caret"></b>
			</a>
				<ul class="dropdown-menu">
					<li>
						<a href="profile.php"><i class="fa fa-fw fa-user"></i> Profile</a>
					</li>
					<li class="divider"></li>
					<li>
						<a href="../includes/logout.php"><i class="glyphicon glyphicon-log-out"></i> Log Out</a>
					</li>
				</ul>
		</li>
	</ul>
			
	<!-- Sidebar Menu Items - These collapse to the responsive navigation menu on small screens -->
	<div id="sidebar" class="collapse navbar-collapse navbar-ex1-collapse">
		<ul class="nav navbar-nav side-nav">
			<li>
				<a id="dashboardlink" href="index.php"><i class="fa fa-fw fa-dashboard"></i> Dashboard</a>
			</li>
			
			<li>
				<a id="postslink" href="javascript:;" data-toggle="collapse" data-target="#posts_dropdown">
					<i class="fa fa-fw fa-clipboard"></i> Posts <i class="fa fa-fw fa-caret-down"></i>
				</a>
				<ul id="posts_dropdown" class="collapse">
					<li>	<a href="posts.php?source=">View All Posts</a></li>
					<li>	<a href="posts.php?source=add_post">Add Post</a></li>
				</ul>
			</li>
			
			<!-- non-Administrators can only view their own comments -->
			<?php
				$comment_author = $_SESSION['username'];
				if($_SESSION['role'] != 'Administrator') {
					$href = "comments.php?author=$comment_author";
				} else {
					$href = 'comments.php';
				}
			?>			
			<li>
				<a id="commentslink" href="<?php echo $href;?>">
					<i class="fa fa-fw fa-comment-o"></i> Comments
				</a>
			</li>

			<li>
				<li><a id="categorieslink" href="categories.php"><i class="fa fa-fw fa-folder-open-o"></i> Categories</a></li>
			</li>
			
			<li>
				<a id="userslink" href="javascript:;" data-toggle="collapse" data-target="#users_dropdown">
					<i class="fa fa-fw fa-users"></i> Users <i class="fa fa-fw fa-caret-down"></i>
				</a>
				<ul id="users_dropdown" class="collapse">
					<li><a href="users.php?source=">View All Users</a></li>
					<li>	<a href="users.php?source=add_user">Add User</a></li>
				</ul>
			</li>

			
			
			<li>
				<a id="profilelink" href="profile.php"><i class="fa fa-fw fa-file-text-o"></i> Profile</a>
			</li>

		</ul>
	</div>		<!-- /.navbar-collapse -->
</nav>