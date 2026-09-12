<?php 
require_once '../core/init.php'; 

if(isset($_POST['search_term']) == true && empty($_POST['search_term']) == false){ 
    $search_term = $_POST['search_term']; 
    $search_term = mysql_real_escape_string($search_term); 
   
    $query = mysql_query("SELECT `user_id`, `username` FROM `users` WHERE `username` LIKE '%$search_term%'"); 
    
    while ($row = mysql_fetch_assoc($query)) { 
        echo '<li data-id="' . htmlspecialchars($row['user_id']) . '">' . htmlspecialchars($row['username']) . '</li>'; 
    } 
} 
?>
