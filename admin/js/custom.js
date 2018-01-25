$(document).ready(function () {
	
	// select/de-select all checkboxes on view_all_posts.php
	
	$('#select_all').click(function () {
		if (this.checked) {
			$('.checkboxes').each(function () {
				this.checked = true;
			})
		} else {
			$('.checkboxes').each(function () {
				this.checked = false;
			})
		}
	})	
	
	// when a page loads, remove 'active' class from all sidebar links in
	// admin_nav.php; then use the 1st 3 characters of <small> tag to 
	// identify active page
	
	// '#sidebar li a' background is then set to be same as '#page-wrapper'
	// in sb-admin.css
	 
	$('#sidebar li a').removeClass("active");	
	var x = $('#small').text();
	x = x.substring(1, 4);

	switch(x) {
		case 'Pro':
			$('#profilelink').addClass("active");
			break;
		case 'Com':
			$('#commentslink').addClass("active");
			break;
		case 'Use':
			$('#userslink').addClass("active");
			break;
		case 'Cat':
			$('#categorieslink').addClass("active");	
			break;
		case 'Pos':
			$('#postslink').addClass("active");
			break;
		case 'Wel':
			$('#dashboardlink').addClass("active");
			break;
		default:
	}

	var div_box = "<div id='load-screen'><div id='loading'></div></div>";
	
	$("body").prepend(div_box);
	
	$("#load-screen").delay(300).fadeOut(300, function () {
		$(this).remove();
	})	;
})



