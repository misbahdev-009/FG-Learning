<?php
include 'core/init.php';
logged_in_redirect();

if(empty($_POST) === false){
 $identity = $_POST['identity'];
 $password = $_POST['password'];
 if(empty($identity)===true || empty($password)===true){
 	$errors[]='You need to enter username or email and password';
 } else if (user_exists($identity) === false && email_exists($identity) === false) {
    $errors[] = 'We can\'t find this username or email. Have you registered?';
}else if(user_active($identity) === false){      
 	$errors[]='You haven\'t activated your account!';
 }
 else{
 	if(strlen($password) > 32){
 		$errors[] = 'Password is to long!';
 	}
 	$login = login($identity, $password);
 	   if($login === false){
          $errors[] = 'This username or email and password combination is incorrect'; 
 	   }else{
 	   	$_SESSION['user_id'] = $login;
 	   	header('location: index.php');
 	   	exit();
 	   }
 }
}else{
//echo 'landed';
$errors[] = 'No information received!';
}
include 'includes/overall/header.php'; 
if (empty($errors) === false){
?>

<h2>We tried to log you in, But.....</h2>

<?php 
echo output_errors($errors);
}
include 'includes/overall/footer.php';
?>
