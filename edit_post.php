<?php 
include 'core/init.php';
protect_page();

include 'includes/overall/header.php';
if (has_access($session_user_id, 'admin') === false && has_access($session_user_id, 'editor') === false) {
    header('Location: index.php');
    exit();
}

$post = null;
$post_id = null;

if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug = $_GET['slug'];
    $post_id = get_post_id_by_slug($slug);
    
    if ($post_id) {
        $post = get_post_by_id($post_id);
    }
}

if (!$post) {
    die("Error: Post not found or invalid slug.");
}

$fileData = [];

if (isset($_POST['submit'])) {

    // echo "<pre>"; print_r($_POST); print_r($_FILES); die();

    $title   = htmlspecialchars(strip_tags($_POST['title']));
    $content = htmlspecialchars(strip_tags($_POST['content']));
    $date    = $_POST['date'];

    // $hasImage = false;

    if (empty($_FILES['media']['name']) === false) {
        // $hasImage = true;
        $allowed = array('jpeg','gif', 'jfif', 'mp4', 'mov','png','jpg');

        $file_name  = $_FILES['media']['name'];
        $file_parts = explode('.', $file_name);

        $fileData["ext"]       = strtolower(end($file_parts));
        $fileData["temp_path"]  = $_FILES['media']['tmp_name']; 
        if (in_array($fileData["ext"], $allowed) === false) {
            $errors[] = 'Incorrect file type. Allowed: ' . implode(', ', $allowed);
        } 
    }
if (empty($errors)) {
    updatePostData($post_id,$fileData, $title, $content, $date);
    header('Location: posts.php');
    exit(); 
}else{
    echo output_errors($errors);
}

}
?>

<main>
    <h1>Edit Post!</h1>

    <form action="" method="POST" enctype="multipart/form-data">
        <div class="post_data">
            <label for="title">Post Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
        </div>

        <div class="post_data">
            <label for="content">Post Content (Description):</label>
            <textarea id="content" name="content" rows="10" required><?php echo htmlspecialchars($post['description']); ?></textarea>
        </div>

        <div class="post_data">
            <label for="date">Date:</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($post['date']); ?>" required>
        </div>

        <div class="post_data">
            <label for="media">Upload Media (Leave empty to keep existing media):</label>
            <input type="file" id="media" name="media" accept="image/*,video/*">
            <?php if (!empty($post['path'])): ?>
                <p style="margin-top: 5px; color: #555;">
                    Current file: <strong><?php echo $post['path']; ?></strong>
                </p>
            <?php endif; ?>
        </div>

        <button type="submit" name="submit">Edit Post</button>
    </form>
</main>

<?php include 'includes/overall/footer.php'; ?>
