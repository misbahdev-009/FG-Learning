<?php 
include 'core/init.php';
protect_page();
if(empty($_POST) === false){
$required_fields  = array('current_password', 'password', 'password_again');
foreach ($_POST as $key => $value) {
//echo $key;
	if(empty($value) && in_array($key,$required_fields) === true){
	$errors[]= "Field marked with aesterisk are required.";
	break 1;
	}
}
if (md5($_POST['current_password']) === $user_data['password']) {
	  IF(trim($_POST['password']) !== trim($_POST['password_again'])){
     $errors[] = 'Your new passwords do not match';
	  }else if (strlen($_POST['password']) < 6) {
	  	$errors[]= "Your password must be atleast 6 characters";	
	  }
}else{
	$errors[]= "Your current password is incorrect";
} 
//echo $user_data['password'];
//print_r($errors);
}
include 'includes/overall/header.php';?>
<main>
<h1>Change Password</h1>
</main> 
<?php
if(isset($_GET['success']) === true && empty($_GET['success']) === true){
echo 'Your password has been changed successfully!';
	}else{
		if(isset($_GET['force']) === true && empty($_GET['force']) === true){
?>
<p>You must have to change your password now as you have requested</p>
<?php
		}
if(empty($_POST) === false && empty($errors) === true){
//post the data 
//echo 'ok';
change_password($session_user_id, $_POST['password']);
header('location: changepassword.php?success');
	}else if(empty($errors) === false){
	//output errors
	echo output_errors($errors);
	}
?>
<form action = "" method="POST">
<ul>
	<li>
		current password*: <br>
		<input type="password" name="current_password">
	</li>
	<li>
		new password*: <br>
		<input type="password" name="password">
	</li>
	<li>
		new password again*: <br>
		<input type="password" name="password_again">
	</li>
	<li>
		 
		<input type="submit" value="change password">
	</li>
</ul>
</form>
<?php
} include 'includes/overall/footer.php'; 
?>