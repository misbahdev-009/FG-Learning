<?php
//        function change_profile_image($user_id, $file_temp, $file_extn){
//                //Generate the file path
//                $file_path = 'images/profile/' . substr(md5(time()), 0, 10) . '.' . $file_extn;
//                move_uploaded_file($file_temp, $file_path);
//                mysql_query("UPDATE `users` SET `profile` = '" . $file_path . "' WHERE `user_id` = " . (int)$user_id);
//        }
function change_profiles_image($user_id, $file_temp, $file_extn){

     //converting user id to integer.g

     $user_id = (int)$user_id;

    // 1. Get the OLD profile image from database
    $query = mysql_query("
        SELECT `profile`
        FROM `users`
        WHERE `user_id` = $user_id
    ");
    //fetching all rows

    $row = mysql_fetch_assoc($query);

    $old_file = $row['profile'];


    // 2. Generate NEW file path randomly
    $file_path = 'images/profile/' . substr(md5(time()), 0, 10) . '.' . $file_extn;


    // 3. Upload NEW image
    if (move_uploaded_file($file_temp, $file_path)) {


        // 4. Update database
        $update = mysql_query("
            UPDATE `users`
            SET `profile` = '$file_path'
            WHERE `user_id` = $user_id
        ");


        // 5. If database update succeeds, delete OLD image
        if ($update) {

            if (
                !empty($old_file) &&
                file_exists($old_file)
            ) {

                unlink($old_file);

            }

        } else {

            // If database update failed,
            // then delete the newly uploaded image
            if (file_exists($file_path)) {
                unlink($file_path);
            }

        }

    }
}



        function mail_users($subject, $body) {
                $query = mysql_query("SELECT `email`, `first_name` FROM `users` WHERE `allow_email` = 1");

                while (($row = mysql_fetch_assoc($query)) !== false) {
                    $email_body = "Hello " . $row['first_name'] . ",\n\n" . $body;
                    email($row['email'], $subject, $email_body);
                }
        }

        function has_access($user_id, $role){
               $user_id    = (int)$user_id;
               $role      = mysql_real_escape_string($role);
               return (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `user_id` = $user_id AND `role` = '$role'"), 0) == 1) ? true:false;
        }

        function recover($mode, $email){
                $mode = sanitize($mode);
                $email = sanitize($email);

                $user_data = user_data(user_id_from_email($email), 'first_name','user_id', 'username');

                if($mode == 'username'){
        	//recover username
                       email($email, "Your username", "Hello, " . $user_data['first_name'] ."\n\nYour username is: ". $user_data['username'] ." \n\nPhpHome ");
               }else if($mode == 'password'){
        	//recover password
                       $generated_password = substr(md5(rand(999, 999999)), 0, 8);
                       change_password($user_data['user_id'], $generated_password);

                       update_user($user_data['user_id'], array('password_recover' => '1'));

                       email($email, "Your password", "Hello, " . $user_data['first_name'] ."\n\nYour new password is: ". $generated_password ." \n\nPhpHome ");
               }
        } 

        function update_user($user_id, $update_data){ 
        	$update = array();
        	array_walk($update_data, 'array_sanitize');

            foreach ($update_data as $fields => $data) {
            	$update[] = '`' .$fields .'` = \''.$data. '\'';
            }
            //print_r($update);
            echo implode(',', $update);
            //die();
            mysql_query("UPDATE `users` SET " .implode(', ', $update). " WHERE `user_id` = $user_id");
        }
        function activate($email, $email_code){
              $email      = mysql_real_escape_string($email);
              $email_code = mysql_real_escape_string($email_code);

              if (mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email`='$email' AND `email_code` = '$email_code' AND `active` =0"), 0) ==1) {
                     mysql_query("UPDATE `users` SET `active` = 1 WHERE `email` = '$email'");
                     return true;
             }else{
                     return false;
             }
        }
        function change_password($user_id, $password){
        	$user_id  = (int)$user_id;
                $password = md5($password);

                mysql_query("UPDATE `users` SET `password` = '$password', `password_recover` = 0 WHERE `user_id` = $user_id");
        }
        function register_user($register_data){
        	$register_data['password'] = md5($register_data['password']);
        	array_walk($register_data, 'array_sanitize');
        	//print_r($register_data);
        	$fields = '`' .implode("`,`",array_keys($register_data)) . '`';
        	$data   = '\'' .implode('\',\'',$register_data) . '\'';
        	//print_r($fields);
        	//echo "INSERT INTO `users`($fields) VALUES ($data)";
        	///die();
        	mysql_query("INSERT INTO `users`($fields) VALUES ($data)");
        	email($register_data['email'], 'Activate Your Account', "Hy " .$register_data['first_name']. ",\n\nYou need to activate your account, so use the link below:". "\n\nhttp://localhost/rl/activate.php?email=".$register_data['email']."&email_code=".$register_data['email_code']."\n\nwafazahra4456@gmail.com");
        }
        function user_count(){ 
        	return mysql_result(mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `active` = 1"), 0);
        }
        function user_data($user_id){
               $data          = array();
               $user_id       = (int)$user_id;
               $func_num_args = func_num_args();
               $func_get_args = func_get_args();
               if( $func_num_args > 1){
                   unset( $func_get_args[0]);
                   $fields    = '`' .implode("`,`",$func_get_args) . '`';
                     //echo "SELECT $fields FROM users WHERE `user_id` = $user_id";
                     //die();
                   $data     = mysql_fetch_assoc(mysql_query("SELECT $fields FROM users WHERE `user_id` = $user_id"));
                      //print_r($data);
                     // die();
                   return $data;

           }
        }
        function GetPostData() {

            $data       = array();
            $query      = mysql_query("SELECT `post_id`,`slug`,`title`,`description`,`path`,`user_id`,`date`FROM posts ");
            while ($row = mysql_fetch_assoc($query)) {
                $data[] = $row;
        }
        return $data;
            // echo "<pre>"; print_r($data); die();
        }
        function get_post_by_id($post_id){
                $data    = array();
                $post_id = (int)$post_id;
                $data    = mysql_fetch_assoc(mysql_query("SELECT `post_id`,`slug`,`title`,`description`,`path`,`user_id`,`date`FROM posts WHERE `post_id` = $post_id"));
                return $data;
        }

        function get_post_id_by_slug($slug){
                $slug  = sanitize($slug);
                $query = mysql_query("SELECT `post_id`FROM posts WHERE `slug` = '$slug'")  or die("Database Error: " . mysql_error());
                return mysql_result($query,0, 'post_id' );
        }

        function insertPostData($user_id, $file_temp, $file_extn,$title,$content,$date){

                $file_path      = 'images/posts/' . substr(md5(time()), 0, 10) . '.' . $file_extn;
                $formatted_date = date('Y-m-d', strtotime($date));
                $user_id        = (int)$user_id;
                $slug           = makeSlug($title);
                move_uploaded_file($file_temp, $file_path);


                mysql_query("INSERT INTO `posts` (`slug`, `title`, `description`, `path`, `user_id`,`date`) VALUES ('$slug','$title', '$content', '$file_path', $user_id, '$formatted_date')");

                echo "ERROR" .mysql_error();
        }

//        function updatePostData($post_id,$fileData,$title,$content,$date){
//
//                $formatted_date = date('Y-m-d', strtotime($date));
//                $post_id        = (int)$post_id;
//                $slug           = makeSlug($title);
//
//                $fields = "`slug` = '$slug',`title` = '$title', `description` = '$content', `date` = '$formatted_date'";
//
//                if(!empty($fileData)){
//                        $file_path      = 'images/posts/' . substr(md5(time()), 0, 10) . '.' . $fileData["ext"];
//                        move_uploaded_file($fileData["temp_path"], $file_path);
//                        $fields.=",`path` = '$file_path'";
//                }
//                mysql_query("UPDATE `posts` SET $fields WHERE `post_id` = $post_id");
//                echo "ERROR: " . mysql_error();
//        }

function updatePostData($post_id, $fileData, $title, $content, $date){

    $post_id = (int)$post_id;

    $formatted_date = date('Y-m-d', strtotime($date));

    $slug = makeSlug($title);


    // Escape data
    $title   = mysql_real_escape_string($title);
    $content = mysql_real_escape_string($content);
    $slug    = mysql_real_escape_string($slug);


    /*
    |--------------------------------------------------------------------------
    | GET OLD IMAGE
    |--------------------------------------------------------------------------
    */

    $old_file = '';

    if (!empty($fileData)) {

        $query = mysql_query("
            SELECT `path`
            FROM `posts`
            WHERE `post_id` = $post_id
        ");

        $row = mysql_fetch_assoc($query);

        $old_file = $row['path'];
    }


    /*
    |--------------------------------------------------------------------------
    | NORMAL POST FIELDS
    |--------------------------------------------------------------------------
    */

    $fields = "
        `slug` = '$slug',
        `title` = '$title',
        `description` = '$content',
        `date` = '$formatted_date'
    ";


    /*
    |--------------------------------------------------------------------------
    | NEW IMAGE
    |--------------------------------------------------------------------------
    */

    $new_file = '';

    if (!empty($fileData)) {

        $new_file = 'images/posts/' .
            substr(md5(time()), 0, 10) .
            '.' .
            $fileData["ext"];


        // Upload new image
        if (move_uploaded_file(
            $fileData["temp_path"],
            $new_file
        )) {

            $fields .= ", `path` = '$new_file'";

        } else {

            echo "ERROR: Image upload failed.";
            return;

        }
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE DATABASE
    |--------------------------------------------------------------------------
    */

    $update = mysql_query("
        UPDATE `posts`
        SET $fields
        WHERE `post_id` = $post_id
    ");


    /*
    |--------------------------------------------------------------------------
    | IF DATABASE UPDATE SUCCESSFUL
    |--------------------------------------------------------------------------
    */

    if ($update) {

        // Delete old image ONLY if a new image was uploaded
        if (
            !empty($new_file) &&
            !empty($old_file) &&
            file_exists($old_file)
        ) {

            unlink($old_file);

        }

    } else {

        /*
        |--------------------------------------------------------------------------
        | DATABASE UPDATE FAILED
        |--------------------------------------------------------------------------
        |
        | We already uploaded the new image.
        | Since DB update failed, remove the new image
        | so we don't leave an unused file.
        |
        */

        if (
            !empty($new_file) &&
            file_exists($new_file)
        ) {

            unlink($new_file);

        }

        echo "ERROR: " . mysql_error();
    }

}

        function makeSlug($title){
                $title      = sanitize($title);
                $titleParts = explode(' ', $title);
                $title      = implode('-', $titleParts);
                return $title;
        } 

        function delete_post_by_id($post_id){
                $post_id        = (int)$post_id;
                mysql_query("DELETE FROM `posts` WHERE `post_id` = $post_id");
              //  echo "ERROR: " . mysql_error(); die("mn nhi chalon ga");
        }
        // function comment_data($comment_id){
        //          $data          = array();
        //          $comment_id    = (int)$comment_id;
        //          $func_num_args = func_num_args();
        //          $func_get_args = func_get_args();
        //         if( $func_num_args > 1){
        //              unset( $func_get_args[0]);
        //              $fields = '`' .implode("`,`",$func_get_args) . '`';
        //              //echo "SELECT $fields FROM users WHERE `comment_id` = $comment_id";
        //              //die();
        //               $data = mysql_fetch_assoc(mysql_query("SELECT $fields FROM posts WHERE `comment_id` = $comment_id"));
        //               //print_r($data);
        //              // die();
        //               return $data;

        //        }
        // }

        function logged_in(){
        	return(isset($_SESSION['user_id'])) ? true : false;
        }

        function user_exists($username){
        	$username = sanitize($username);
                $query    = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `username`='$username'");

                return(mysql_result($query, 0) == 1) ? true : false;
        }

        function email_exists($email){
        	$email = sanitize($email);
        	$query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE `email`='$email' ");

        	return(mysql_result($query, 0) == 1) ? true : false;
        }

        function user_active($identity){
        	$identity = sanitize($identity);
        	$query    = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE (`username` = '$identity' OR `email` = '$identity') AND `active` = 1");
        	//condition ? true: false;
        	return(mysql_result($query,0) == 1) ? true : false;
        }

        function user_id_from_username($username){
                $username = sanitize($username);
                $query=mysql_query("SELECT `user_id` FROM `users` WHERE `username` = '$username' LIMIT 1");
                return mysql_result($query,0, 'user_id' );
        }
        function username_from_user_id($user_id){
                $user_id = (int)$user_id;
                $query   =  mysql_query("SELECT `first_name` FROM `users` WHERE `user_id` = '$user_id' LIMIT 1");
                return mysql_result($query,0, 'first_name' );
        }
        function user_id_from_email($email){
                $email = sanitize($email);
                $query=mysql_query("SELECT `user_id` FROM `users` WHERE `email` = '$email' LIMIT 1");
                return mysql_result($query,0, 'user_id' );
        }


        function login($identity, $password){
           $identity = sanitize($identity);  
           $user_id = user_id_from_username($identity);
           if(!$user_id){
            $user_id   = user_id_from_email($identity);
        }
        $password = md5($password);
                   // print_r($password); die();
        $query = mysql_query("SELECT COUNT(`user_id`) FROM `users` WHERE(`username` = '$identity' OR `email` = '$identity')  AND `password` = '$password' LIMIT 1");

        return(mysql_result($query, 0 ) == 1) ? $user_id : false;
        }
        function destroyUser($usrId){

            //delete user code goes
        }

        function userActivationViaWhatsapps($userId){
            //code goes here jo
        }

?>