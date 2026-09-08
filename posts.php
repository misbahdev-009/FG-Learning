<?php

include 'core/init.php';
protect_page();
//delete post
if (isset($_POST['delete_post'])) {

    $slug = mysql_real_escape_string($_POST['slug']);

    $query = mysql_query("SELECT `path`FROM `posts`WHERE `slug` = '$slug'");

    $row = mysql_fetch_assoc($query);

    $old_file = $row['path'];
    $delete = mysql_query("DELETE FROM `posts` WHERE `slug` = '$slug'");
    if ($delete) {
        if (!empty($old_file) &&file_exists($old_file)) {

            unlink($old_file);

        }

        echo "success";

    } else {

        echo "error";

    }

    exit();
}


include 'includes/overall/header.php';
if(has_access($session_user_id, 'admin') === true OR has_access($session_user_id, 'editor') === true
){ ?>

    <b>Want to post something?</b>

    <a href="posts_data.php">
        <button type="button">post</button>
    </a>

<?php } ?>

    <div id="successMessage" class="success-message">
        Post deleted successfully.
    </div>


<?php

foreach ($GetpostData as $key => $post) {

    ?>

    <main id="post-<?php echo $post['slug']; ?>">

        <div class="container">

            <article>

                <div class="posts-left">

                    <img
                            src="<?php echo $post['path']; ?>"
                            alt="<?php echo $post['title']; ?>"
                    >

                </div>


                <div class="posts-right">

                    <p>
                        <a href="post_details.php?slug=<?php echo $post['slug']; ?>">
                            <?php echo $post['title']; ?>
                        </a>
                    </p>


                    <p class="post-meta">

                        Published on

                        <?php
                        echo date('F d, Y',strtotime($post['date']));
                        ?> by<?php $publisher = username_from_user_id($post['user_id']);
                        echo $publisher;
                        ?>

                    </p>
                    <p>
                        <?php echo $post['description']; ?>
                    </p>

                </div>
                <?php

                if(has_access($session_user_id, 'admin') === true OR has_access($session_user_id, 'editor') === true
                ){

                    ?>
                    <a href="edit_post.php?slug=<?php echo $post['slug']; ?>">

                        <button type="button">
                            Edit
                        </button>

                    </a>

                    <button type="button" onclick="showDeletePrompt('<?php echo $post['slug']; ?>')" >
                        Delete
                    </button>
                <?php } ?>
            </article>
        </div>
    </main>
    <?php

}

?>
    <div id="deleteModal" class="modal-overlay">

        <div class="modal-content">

            <h3>Are you sure?</h3>

            <p>
                Do you really want to delete this post?
            </p>

            <div class="modal-buttons">

                <button
                        id="confirmYes"
                        type="button"
                >
                    Yes
                </button>


                <button
                        id="confirmNo"
                        type="button"
                        onclick="closePrompt()"
                >
                    No
                </button>

            </div>

        </div>

    </div>
    <script>
        var currentDeleteSlug = null;

        function showDeletePrompt(slug) {
            currentDeleteSlug = slug;

            document.getElementById("deleteModal").style.display = "block";
            document.getElementById("confirmYes").onclick = function() {
                executeDelete(currentDeleteSlug);
            };
        }

        function closePrompt() {

            document.getElementById("deleteModal").style.display = "none";

            currentDeleteSlug = null;

        }
        function executeDelete(slug) {
            closePrompt();


            // Create AJAX request
            var xhr = new XMLHttpRequest();


            // Send request to THIS SAME PAGE
            xhr.open("POST", window.location.href, true);


            // Tell server that we are sending form data
            xhr.setRequestHeader(
                "Content-Type",
                "application/x-www-form-urlencoded"
            );


            // When server responds
            xhr.onreadystatechange = function() {

                if (xhr.readyState === 4 && xhr.status === 200) {

                    // Check server response
                    if (xhr.responseText.trim() === "success") {
                         // Find the post
                         var postElement = document.getElementById("post-" + slug);
                          // Remove post from page
                        if (postElement) {

                            postElement.style.opacity = "0";

                            postElement.style.transition = "opacity 0.5s";
                            setTimeout(function() {
                                postElement.remove();

                            }, 500);

                        }
                        // Show success message
                        var message = document.getElementById(
                            "successMessage"
                        );

                        message.style.display = "block";

                        // Hide success message after 3 seconds
                        setTimeout(function() {

                            message.style.display = "none";

                        }, 3000);
                    } else {

                        alert("Delete failed.");

                    }

                }

            };


            // Send slug to PHP
            xhr.send(
                "delete_post=1&slug=" + encodeURIComponent(slug)
            );

        }

    </script>


<?php

include 'includes/overall/footer.php';

?>