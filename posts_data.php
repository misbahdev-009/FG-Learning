<?php 
include 'core/init.php';
protect_page();
editor_protect();
include 'includes/overall/header.php';?>
<main>
<h1>Create a New Post!</h1>
</main> 
<?php 
if (isset($_POST['submit'])) {
    $title   = htmlspecialchars(strip_tags($_POST['title']));
    $content = htmlspecialchars(strip_tags($_POST['content']));
    $date    =$_POST['date'];
    if (empty($_FILES['media']['name']) === true) {
        echo 'Please choose a file';
    } else {
        $allowed = array('jpg', 'png', 'gif', 'jpeg', 'jfif', 'mp4', 'mov');

        $file_name = $_FILES['media']['name'];
        $file_parts = explode('.', $file_name);
        $file_extn = strtolower(end($file_parts));
        $file_temp = $_FILES['media']['tmp_name'];   

        if (in_array($file_extn, $allowed) === true) {
            insertPostData($session_user_id, $file_temp, $file_extn,$title,$content,$date);
            header('Location: posts.php');
            exit(); 
        } else {
            echo 'Incorrect file type. Allowed: ';
            echo implode(', ', $allowed);
        }
    }
}
?>

    <form action=" " method="POST" enctype="multipart/form-data">
        
        <div class="post_data">
            <label for="title">Post Title:</label>
            <input type="text" id="title" name="title" required>
        </div>

        <div class="post_data">
            <label for="content">Post Content(Description):</label>
            <textarea id="content" name="content" rows="10" ></textarea>
        </div>
        <div class="post_data">
            <label for="date">date:</label>
            <input type="date" id="date" name="date" required>
        </div>

        <div class="post_data">
            <label for="media">Upload Media (Image or Video):</label>
            <!-- accept attribute helps filter files in the user's file picker -->
            <input type="file" id="media" name="media" accept="image/*,video/*">
        </div>

        <button type="submit" name="submit">Publish Post</button>
    </form>
<?php include 'includes/overall/footer.php';
?>
