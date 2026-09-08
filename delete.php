<?php 
include 'core/init.php';
protect_page();

// 1. Authorization Access Check (Matches your Edit page)
if (has_access($session_user_id, 'admin') === false && has_access($session_user_id, 'editor') === false) {
    header('Location: index.php');
    exit();
}

$post = null;

// 2. Look up the post details using the text slug
if (isset($_GET['slug']) && !empty($_GET['slug'])) {
    $slug    = $_GET['slug'];
    $post_id = get_post_id_by_slug($slug);
    
    if ($post_id) {
        $post = get_post_by_id($post_id);
    }
}

// Redirect if the post slug is completely invalid
if (!$post) {
    die("Error: Post not found or invalid slug.");
}

// 3. Handle the confirmation submission
if (isset($_POST['confirm_delete'])) {
    if (delete_post_by_slug($slug)) {
        header('Location: posts.php');
        exit();
    } else {
        $error_msg = "Something went wrong. Could not delete the post.";
    }
}

include 'includes/overall/header.php';
?>

<main>
    <h1>Delete Post</h1>
    
    <?php if (!empty($error_msg)): ?>
        <p style="color: red; font-weight: bold;"><?php echo $error_msg; ?></p>
    <?php endif; ?>

    <div class="post_data" style="background: #fff5f5; border: 1px solid #ffcccc; padding: 20px; border-radius: 5px;">
        <p style="font-size: 1.1em; color: #cc0000;">
            Are you absolutely sure you want to permanently delete the following post?
        </p>
        
        <h3 style="margin: 15px 0 5px 0;"><?php echo htmlspecialchars($post['title']); ?></h3>
        <p style="color: #666; font-size: 0.9em; margin-bottom: 20px;">
            Published on: <?php echo date('F d, Y', strtotime($post['date'])); ?>
        </p>

        <!-- The Confirmation Action Form -->
        <form action="" method="POST">
            <button type="submit" name="confirm_delete" style="background-color: #d9534f; color: white; border: none; padding: 10px 20px; cursor: pointer; border-radius: 4px; font-weight: bold;">
                Yes, Delete Permanently
            </button>
            <a href="posts.php" style="margin-left: 15px; text-decoration: none; color: #555; background: #e6e6e6; padding: 10px 20px; border-radius: 4px; font-weight: bold;">
                Cancel
            </a>
        </form>
    </div>
</main>

<?php include 'includes/overall/footer.php'; ?>
