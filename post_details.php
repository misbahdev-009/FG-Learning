<?php
include 'core/init.php';
protect_page();
include 'includes/overall/header.php';

    if (isset($_GET['slug']) && !empty($_GET['slug'])) {
        $slug    = $_GET['slug'];
        $post_id = get_post_id_by_slug($slug);
        
        if ($post_id) {
            $post = get_post_by_id($post_id);
        } else {
            die("Post not found.");
        }
    } else {
        die("Invalid post request.");
    }
?>
<h2>Post Details</h2>
<div class="single-post-page">
<main>
    <div class="full-post-container">
        <a href="posts.php" class="back-link">&larr; Back to all posts</a>
        
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        
        <div class="full-post-meta">
            Published on <?php echo date('F d, Y', strtotime($post['date'])); ?> by <?php echo username_from_user_id($post['user_id']); ?>
        </div>
        
        <?php if (!empty($post['path'])): ?>
            <img class="full-post-image" src="<?php echo htmlspecialchars($post['path']); ?>" alt="<?php echo htmlspecialchars($post['title']); ?>">
        <?php endif; ?>

        <div class="full-post-content">
            <?php echo nl2br(htmlspecialchars($post['description'])); ?>
        </div>
    </div>
</main>
</div>
<?php include 'includes/overall/footer.php'; ?>
