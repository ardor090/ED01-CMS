<?php
// turn on output buffering in order to use header()
	ob_start();
?>
<?php include '../includes/database.php';?>
<?php include 'admin_functions.php';?>

<?php session_start();?>
<?php
/*
if(isset($_SESSION['role'])) {
	if($_SESSION['role'] !== 'Administrator') {
		header('Location: ../index.php?login='.$_SESSION['userid']);		
	}
} else {
	header('Location: ../index.php?login=0');
}
*/
?>
<!DOCTYPE html>
<html lang="en">
	<head>
	
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="">
		<meta name="author" content="Chi Lin" >
		
		<title><?php echo SITENAME;?> Admin Section</title>
		
		<!-- Bootstrap Core CSS -->
		<link href="css/bootstrap.min.css" rel="stylesheet">
		
		<!-- Custom CSS -->
		<link href="css/sb-admin.css" rel="stylesheet">
		<link href="css/custom.css" rel="stylesheet">
		
		<!-- Custom Google Font -->
		<link href='https://fonts.googleapis.com/css?family=Roboto+Condensed' rel='stylesheet' type='text/css'>
		
		<!-- Custom Fonts -->
		<link href="font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css">
		
		<!-- Google Charts -->
		<script src="https://www.google.com/jsapi"></script>
		
		<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
		    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
		    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
		<![endif]-->
	
	</head>
