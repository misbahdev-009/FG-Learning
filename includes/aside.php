<?php 
// Get the current file name (e.g., 'posts.php')
$current_page = basename($_SERVER['SCRIPT_NAME']); 

// Only show the sidebar if the current page is NOT posts.php
if ( $current_page !== 'posts.php' && $current_page !== 'post_details.php' && $current_page !== 'delete_post.php' && $current_page !== 'edit_post.php'){ 
?>
    <aside> 
        <?php 
        if ( logged_in() === true ) {
            include 'includes/widgets/loggedin.php';
        } else {
            include 'includes/widgets/login.php';
        }
        include 'includes/widgets/user_count.php';
        ?>
    </aside>
<?php } ?>
