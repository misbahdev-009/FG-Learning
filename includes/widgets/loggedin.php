<div class="widget">
<h2>Hy, <?php echo $user_data['first_name']; ?>!</h2>
<div class="inner">
<div class="profile">
<?php

if (isset($_FILES['profile']) === true) {
   if (empty($_FILES['profile']['name']) === true) {
      echo 'Please choose a file';
   }else{
      $allowed = array('jpg', 'png', 'gif', 'jpeg','jfif');

      $file_name = $_FILES['profile']['name'];
     // $file_extn = strtolower(end(explode('.', $file_name)));
       $file_parts = explode('.', $file_name);
       $file_extn = strtolower(end($file_parts));
       $file_temp = $_FILES['profile']['tmp_name'];   

      if (in_array($file_extn, $allowed) === true) {
         change_profile_image($session_user_id,$file_temp, $file_extn);
         header('location: '. $current_file);
      }else{
          echo 'Incorrect file type. Allowed: ';
             echo implode(', ', $allowed);
      }
              }
}
if(empty($user_data['profile']) === false){
   echo '<img src = "',$user_data['profile'],'" alt = "',$user_data['first_name'],'\'s Profile image">';
}
?>
<form action="" method="POST" enctype="multipart/form-data">
     <input type="file" name="profile"> <br><br>
<input type="submit">
</form>
</div>

   <ul>
   <li>
   <a href="logout.php">Log out</a>
   </li>
   <li>
   <a href="<?php echo $user_data['username'];  ?>">profile</a>
   </li>
   <li>
   <a href="changepassword.php">Change password</a>
   </li>
   <li>
   <a href="settings.php">Settings</a>
   </li>
   </ul>    

</div>
</div>