<?php 
include 'core/init.php';
protect_page();

if (isset($_GET['slug']) == true && !empty($_GET['slug']) == fasle) {
    $slug    = $_GET['slug'];
    $post_id = get_post_id_by_slug($slug);
    
    delete_post_by_id($post_id);
    echo "Deleted successfully";
    exit();
}
?>
