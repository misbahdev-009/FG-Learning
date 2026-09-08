<?php

		function email($to, $subject, $body){
		mail($to, $subject, $body, 'from: wafazahra4456@gmail.com');
		}

		function logged_in_redirect(){
				if(logged_in() === true){
				  header('location: index.php');
				  exit();
		}
		}

		function protect_page(){
				 if(logged_in() === false){
				   header('location: protected.php');
		exit();
		}
		}

		function admin_protect(){
		global $user_data;
				 if (has_access($user_data['user_id'], 'admin') === false) {
					 header('location: index.php');
					 exit();
		}
		}

			function editor_protect(){
		global $user_data;
				 if (has_access($user_data['user_id'], 'editor') === false) {
					 header('location: index.php');
					 exit();
		}
		}

		function array_sanitize(&$item){
		$item   = htmlentities(strip_tags(mysql_real_escape_string($item)));
		}

		function sanitize($data){

		return htmlentities(strip_tags(mysql_real_escape_string($data)));

		}

		function output_errors($errors){
		$output = array();

		return '<ul><li>' . implode('</li><li>',$errors). '</li></ul>';

		}
?>
