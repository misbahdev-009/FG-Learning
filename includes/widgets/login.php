<div class="widget">
    <h3>Log in/Register</h3>
    <div class="inner">
        
    <form action="login.php"  method="Post">
        <ul id="login">
           <li>
           Username or Email:<br>
           <input type="text" name="identity" <?php
      if(isset($_POST['identity']) === true){
         ?>
         value ="<?php echo $_POST['identity']; ?>"
     <?php  } ?>> </li>
           <li>
           Password:<br>
           <input type="Password" name="password"<?php
      if(isset($_POST['password']) === true){
         ?>
         value ="<?php echo $_POST['password']; ?>"
     <?php  } ?>>
           </li>
           <li>
        <input type="submit" value="login">
           </li>
           <li>
        <a href="register.php">Register</a>
           </li>
             <li>
        forgotten your <a href="recover.php?mode=username"> username</a> or <a href="recover.php?mode=password"> password?</a>
           </li>


        </ul>
    </form>

    </div>
</div>