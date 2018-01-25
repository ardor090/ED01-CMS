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
							<small id="small"> Comments Manager</small>
						</h1>
					</div>
				</div>		<!-- /.row -->
								
				<div class="row">
					<div class="col-md-12">
						
<?php include 'admin_includes/admin_view_all_comments.php';?>	

<?php include 'admin_includes/admin_footer.php';?>