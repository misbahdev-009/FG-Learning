<?php 
include 'core/init.php';
logged_in_redirect();
include 'includes/overall/header.php';
if(empty($_POST) === false){
$required_fields  =  array('username','password','password_again','first_name','email');
//echo '<pre>' , print_r($_POST, true), '</pre>';
foreach ($_POST as $key => $value) {
//echo $key;
	if(empty($value) && in_array($key,$required_fields) === true){
	$errors[]= "Field marked with aesterisk are required.";
	break 1;
	}
	}
		if(empty($errors) === true){
		if(user_exists($_POST['username']) === true){
		$errors[] = "Sorry, This username '". $_POST['username']. "'is already taken.";

		}
		if(preg_match("/\\s/",$_POST['username']) == true){
		$errors[] = "Your username must not contain any spaces";
		}
		if(strlen($_POST['password']) < 6){
		$errors[]= "Your password must be atleast 6 characters";	
		}
		if($_POST['password'] !== $_POST['password_again']){
		$errors[]= "Your passwords do not match";	
		}
		if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL) === false) {
		$errors[]= "A valid email address is required";	
		}
		if (email_exists($_POST['email']) === true) {
		$errors[] = "Sorry, This email '". $_POST['email']. "'is in use.";	 
	}
	}
}
//print_r($errors);
?>
<main>
<h1>Register</h1>
</main>
<?php  
if(isset($_GET['success']) && empty($_GET['success'])){
echo 'You\'ve been registered successfully! Please check your email box to activate your account';
	}else{
	if(empty($_POST) === false && empty($errors) === true){
	$register_data = array(
	'username'=> $_POST['username'],
	'password'=> $_POST['password'],
	'first_name'=> $_POST['first_name'],
	'last_name'=> $_POST['last_name'],
	'email'=> $_POST['email'],
	'email_code'=> md5($_POST['username'] + microtime()),
	'role'       => isset($_POST['role']) ? $_POST['role'] : 'Student'
	);
	//print_r($register_data);
	register_user($register_data);
	header('location: register.php?success');
	exit();
	}else if(empty($errors) === false){
	//output errors
	echo output_errors($errors);
	}
	?>
	<form action="" method="POST">
	<ul>
	<li>
	Username*: <br>
	<input type = "text" name = "username" value ="<?php (isset($_POST['username']) === true) ? $_POST['username']:""; ?>">
	</li>
	<li>
	Password*: <br>
	<input type = "password" name = "password">
	</li>
	<li>
	Password Again*: <br>
	<input type = "password" name = "password_again">
	</li>
	<li>
	First Name*: <br>
	<input type = "text" name = "first_name" placeholder="john"
         value ="<?php (isset($_POST['first_name']) === true) ? $_POST['first_name']:""; ?>">
	</li>
	<li>
	Last Name: <br>
	<input type = "text" name = "last_name"
         value ="<?php (isset($_POST['last_name']) === true) ? $_POST['last_name']:""; ?>">
	</li>
	<li>
	Email*: <br>
	<input type = "text" name = "email" 
         value ="<?php (isset($_POST['email']) === true) ? $_POST['email']:""; ?>">
	</li>
	<li> 
	 <label for="role">Select your Role:</label>
     <select name="role" id="role">
    <option value="admin">Admin</option>
    <option value="moderator">Moderator</option>
    <option value="editor">Editor</option>
    <option value="student">Student</option>
    <option value="teacher">Teacher</option>
    </select> 
	</li>
	<li>
	<input type = "submit" value="Register">
	</li>
	</ul>
	</form>
	<?php 
	}
include 'includes/overall/footer.php';?>
