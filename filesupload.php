<?php 

    //die();
    if (isset($_FILES['media']) === true) {
        
        $file_temp = $_FILES['media']['tmp_name'];
        $fileName  = $_FILES['media']['name'];
        $fileSize  = $_FILES['media']['size'];
        $fileType  = $_FILES['media']['type'];
        
        // Extract file extension securely
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Define allowed file extensions
        $allowedImageExts = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
        $allowedVideoExts = ['mp4', 'mov', 'avi', 'mkv'];
        $allowedExtensions = array_merge($allowedImageExts, $allowedVideoExts);

        if (in_array($fileExtension, $allowedExtensions)) {
            // Create a unique name for the file to prevent overwriting existing files
            $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
            
            // Directory where files will be saved (make sure this folder exists and is writeable)
            $uploadFileDir = './uploads/';
            
            // Create directory if it doesn't exist
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $dest_path = $uploadFileDir . $newFileName;

            // Move the file from PHP's temporary folder to your uploads folder
            if(move_uploaded_file($fileTmpPath, $dest_path)) {
                echo "Media uploaded successfully!<br>";
                echo "Saved as: " . $dest_path;
                
                // Here, you would typically insert $title, $content, and $dest_path into your database
            } else {
                echo "There was an error moving the uploaded file.";
            }
        } else {
            echo "Upload failed. Allowed formats: JPG, JPEG, PNG, GIF, WEBP, MP4, MOV, AVI, MKV.";
        }
    } else {
        // Optional file upload or handling specific error codes
        if ($_FILES['media']['error'] !== UPLOAD_ERR_NO_FILE) {
            echo "File upload error code: " . $_FILES['media']['error'];
        } else {
            echo "Post created without media.";
        }
    } else {
    echo "Invalid request method.";
}
?>