<?php 
include 'core/init.php';
include 'includes/overall/header.php';

    if(isset($_GET['username']) === true && empty($_GET['username']) === false){
    $username     = $_GET['username'];
    if(user_exists($username) === true){
    $user_id      = user_id_from_username($username);
    $profile_data = user_data($user_id , 'first_name', 'last_name', 'email');
    ?>
    <h1><?php echo $profile_data['first_name'];  ?>'s Profile</h1>
    <p><?php echo $profile_data['email']; ?></p>
    <?php
    if (empty($user_data['profile']) === false) {
    echo '<img src="', $user_data['profile'], '" class="user-avatar-left" alt="', $user_data['first_name'], '\'s Profile image">';
    }
    ?>
    <?php
    }else{
    echo 'Sorry, that user doesn\'t exists';
    }
    }else{
    header('Location: index.php');
    exit();
    }

include 'includes/overall/footer.php';?>
