<?php
	// if 'Add Category' submit button is pressed
	if(isset($_POST['addsubmit'])) {
		$new_cat_title = mysqli_real_escape_string($con, $_POST['add_cat_title']);
		
		// get categories from database to check against new one
		$q = "SELECT * FROM cms_categories";
		$cats = mysqli_query($con, $q);
		
		// set class of alert box and alert message
		if($new_cat_title == "") {
			$div_class = "danger";
			$div_msg = "Category Title can not be empty.";										
		} elseif (cat_exists($new_cat_title, $cats, 'cat_title')) {
			$div_class = "danger";
			$div_msg = 'Catgegory "'.$new_cat_title.'" already exists.';
		} else {
			$q = "INSERT INTO cms_categories (cat_title)
						VALUES ('$new_cat_title')";
			$add_result = mysqli_query($con, $q);
			
			$div_info = confirmQuery($add_result, 'insert');
			$div_display 	= $div_info['div_display'];
			$div_class 		= $div_info['div_class'];
			$div_msg 			= $div_info['div_msg'];
		}
	}
?>	
	
<?php
	// if 'Update Category' submit button is pressed
	if(isset($_POST['editsubmit'])) {
		$new_cat_title = mysqli_real_escape_string($con, $_POST['edit_cat_title']);
		$edit_cat_id = $_POST['edit_cat_id'];
		
		// get categories from database to check against new one
		$q = "SELECT * FROM cms_categories";
		$cats = mysqli_query($con, $q);
		
		// set class of alert box and alert message
		if($new_cat_title == "") {
			$div_class = "danger";
			$div_msg = "Category Title can not be empty.";										
		} elseif (cat_exists($new_cat_title, $cats, 'cat_title')) {
			$div_class = "danger";
			$div_msg = 'Catgegory "'.$new_cat_title.'" already exists.';
		} else {
			$q = "UPDATE cms_categories SET cms_categories.cat_title =
						'$new_cat_title' WHERE cms_categories.cat_id = $edit_cat_id";
			$update_result = mysqli_query($con, $q);
			
			$div_info = confirmQuery($update_result, 'update');
			$div_display 	= $div_info['div_display'];
			$div_class 		= $div_info['div_class'];
			$div_msg 			= $div_info['div_msg'];
		}
	}
?>	
	
<?php
	// if 'Delete' button is pressed, delete from database by id and set alert box message
	if(isset($_GET['del'])) {
		$del_id = $_GET['del'];
		$del_query = "DELETE FROM cms_categories WHERE cms_categories.cat_id = $del_id";
		$del_result = mysqli_query($con, $del_query);
		
		$div_info = confirmQuery($del_result, 'delete');
		$div_display 	= $div_info['div_display'];
		$div_class 		= $div_info['div_class'];
		$div_msg 			= $div_info['div_msg'];
	}
?>	

<?php
	// if 'Edit' button is pressed, populate form to edit
	if(isset($_GET['edit'])) {
		$edit_id = $_GET['edit'];
		$edit_query = "SELECT * FROM cms_categories
									WHERE cms_categories.cat_id = $edit_id";
		$edit_result = mysqli_query($con, $edit_query);
		// $edit_cat is the array containing 'cat_id' and 'cat_title'
		$edit_cat = mysqli_fetch_array($edit_result);
		
		// this is a special case, so it does not user confirmQuery()
		if(!$edit_cat) {
			$div_class = "danger";
			$div_msg = "Database failed: ".mysqli_error($con);
		} else {
			$div_class = "success";
			$div_msg = 'Category "'.$edit_cat['cat_title'].'" ready for edit.';
			// there's something to edit, so do not disable the update button
			$btn_disabled = "";
		}
	} else {
		// set array to nothing if there's nothing to edit
		$edit_cat = ["cat_id"=>0, "cat_title"=>""];
		// there's nothing to edit, so disable the update button
		$btn_disabled = "disabled";	
	}
?>	