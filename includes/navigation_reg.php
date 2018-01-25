<!-- Navigation -->
<nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
	<div class="container">
		<!-- Brand and toggle get grouped for better mobile display -->
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
				<span class="sr-only">Toggle navigation</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>
			<a class="navbar-brand" href="index.php"><?php echo SITENAME;?></a>
		</div>
		<!-- Collect the nav links, forms, and other content for toggling -->
		<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
			<ul class="nav navbar-nav">
				<li><a href="#">Our Most Popular Categories : </a></li>
			
			<?php
				$q = "SELECT post_cat_id, cat_title, count(post_id) AS n
							FROM cms_posts
							INNER JOIN cms_categories ON post_cat_id = cat_id
							GROUP BY post_cat_id
							ORDER BY n DESC
							LIMIT 3";
				$cats = mysqli_query($con, $q);
				
			?>
				<?php foreach($cats as $cat):?>
				<li><a href="cposts.php?cid=<?php echo $cat['post_cat_id'];?>">
					<?php echo $cat['cat_title'];?></a>
				</li>
				<?php endforeach;?>
 				
			</ul>
			
			<ul class="nav navbar-nav navbar-right">
				<li><a href="contact.php">Contact</a></li>
			</ul>
			
		</div>		<!-- /.navbar-collapse -->
	</div>		<!-- /.container -->
</nav>

