<?php 
    include 'core/init.php';
    include 'includes/overall/header.php';
?>

<main>
<h1>Home page</h1>
</main>

<?php
    if(has_access($session_user_id, 'admin') === true){
        echo 'Admin!';
    }else if(has_access($session_user_id, 'moderator') === true){
        echo 'Moderator';
    }else if(has_access($session_user_id, 'editor') === true){
        echo 'Editor!';
    }else if(has_access($session_user_id, 'student') === true){
        echo 'Student!';
    }else if(has_access($session_user_id, 'teacher') === true){
        echo 'Teacher!';
    }

?> 
<?php
    include 'includes/overall/footer.php';
?>
