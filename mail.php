<?php 
include 'core/init.php'; 
protect_page(); 
admin_protect(); 
include 'includes/overall/header.php';
?> 

<main> 
<h1>Email all users</h1> 
</main> 

<?php 
if (isset($_GET['success']) === true && empty($_GET['success']) === true) { 
?> 
<p>Email has been sent</p> 
<?php 
} else { 
if (empty($_POST) === false) { 
    if (empty($_POST['subject']) === true) { 
        $errors[] = "Subject is required"; 
    } 
    if (empty($_POST['body']) === true) { 
        $errors[] = "Body is required"; 
    } 

    if (empty($errors) === false) { 
        echo output_errors($errors); 
    } else { 
        // SEND EMAIL 
        mail_users($_POST['subject'], $_POST['body']); 
        header('Location: mail.php?success'); 
        exit(); 
    } 
} 
?> 
<form action="" method="POST"> 
    <ul> 
        <li> 
            Subject*: <br> 
            <input type="text" name="subject" value="<?php if(isset($_POST['subject'])) { echo $_POST['subject']; } ?>"> 
        </li> 
        <li> 
            Body*: <br> 
            <textarea name="body"><?php if(isset($_POST['body'])) { echo $_POST['body']; } ?></textarea> 
        </li> 
        <li>
            <input type="submit" value="send"> 
        </li>
    </ul> 
</form> 
<?php 
} 

include 'includes/overall/footer.php'; 
?>
