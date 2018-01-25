<?php
// function to shorten text in posts to a default of 400 characters
function shortenText($text, $chars=350) {
	if(strlen($text) > $chars) {
		$text = substr($text, 0, $chars);
		$text = substr($text, 0, strrpos($text, ' '));
		$text = $text." ...";
	}
	return $text;
}

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