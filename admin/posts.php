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
							<small id="small"> Posts Manager</small>
						</h1>
					</div>
				</div>		<!-- /.row -->
									
				<div class="row">
					<div class="col-md-12">
						
<?php
if(isset($_GET['source'])) {
	$source = $_GET['source'];
} else {
	$source = "";
}
	
switch($source) {
	case 'add_post':
		include 'admin_includes/admin_add_post.php';
		break;
	case 'edit_post':
		include 'admin_includes/admin_edit_post.php';
		break;
	case 'c':
		echo 'c';
		break;
	default:
		include 'admin_includes/admin_view_all_posts.php';
}
										
?>	

<?php include 'admin_includes/admin_footer.php';?>