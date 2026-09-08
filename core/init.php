<?php
ini_set('session.gc_maxlifetime', 1440);
	session_start();
	//error_reporting(0);
	global $session_user_id;

			require 'database/connect.php';
			require 'functions/general.php';
			require 'functions/users.php';

			 $current_file =end(explode('/', $_SERVER['SCRIPT_NAME'])) ;
			//print_r($current_file); die();
	if(logged_in() === true){
			$session_user_id = $_SESSION['user_id'];
		$user_data = user_data($session_user_id,'user_id', 'username','password', 'first_name', 'last_name', 'email', 'password_recover', 'role', 'allow_email', 'profile');
		//$post_data = get_post_by_id('post_id', 'slug', 'title','description', 'path', 'user_id', 'date');

		$GetpostData = GetPostData();
		//echo "<pre>"; print_r($GetpostData); die();
		//$commentData = comment_data('comment_id','description','post_id');

	if(user_active($user_data['username']) === false){
	        session_destroy();
	        header('location: index.php');
	        exit(); 
	        
	}
	if($current_file !== 'changepassword.php' && $user_data['password_recover'] == 1 ){
       header('location: changepassword.php?force');
       exit();
	}
	}
	$errors = array();
?>
