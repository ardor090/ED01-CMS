<?php
	// function to count active users
	function users_online() {
		global $con;
		
		$session = session_id();
		$time = time();
		$time_out_in_seconds = TIMEOUT;
		$time_out = $time - $time_out_in_seconds;
		
		$q = "SELECT * FROM cms_sessions WHERE session = '$session'";
		$r = mysqli_query($con, $q);
		$n = mysqli_num_rows($r);
		
		if($n == 0) {
			$q = "INSERT INTO cms_sessions (session, time) VALUES ('$session', '$time')";
		} else {
			$q = "UPDATE cms_sessions SET time = '$time' WHERE session = '$session'";
		}
		mysqli_query($con, $q);
		
		$q = "SELECT * FROM cms_sessions WHERE time > '$time_out'";
		$r = mysqli_query($con, $q);
		return mysqli_num_rows($r);	
	}



	// function to see if $value already exists in a particular $field
	// in the database query $results
	
	function cat_exists($value, $results, $field) {
		
		$found = false;
		foreach($results as $result) {
			if(strcasecmp($value, $result[$field]) == 0) {
				$found = true;	
				break;
			}
		}
		return $found;
	}
	
	// function to confirm database query and returns an array of
	// for the alert box: $div_display, $div_class, $div_msg or

	function confirmQuery($result, $operation) {
		
		global $con;
		
		if(!$result) {
			return [	'div_class'=>'danger', 
							'div_msg'=> 'Database error: '.mysqli_error($con)];
		} else {
			return [	'div_class'=>'success',
							'div_msg'=>'Database "'.$operation.'" successful.'];
		}
	}
?>	